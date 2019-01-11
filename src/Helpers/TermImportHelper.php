<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\ContentHub\Editor\Commands\Taxonomy\Helpers\WpTerm;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\Redirect\Http\BonnierRedirect;
use Exception;

class TermImportHelper
{
    protected $taxonomy;
    protected $permalinksToRedirect;
    protected $categoryLinksToRedirect;
    protected $currentLocale;

    public function __construct($taxonomy)
    {
        $this->taxonomy = $this->getWpTaxonomy($taxonomy);
    }

    public function importTermAndLinkTranslations($externalTerm)
    {
        $termIdsByLocale = collect($externalTerm->name)->map(function ($name, $languageCode) use ($externalTerm) {
            if (!collect(LanguageProvider::getSimpleLanguageList())->contains($languageCode)) {
                return null;
            }
            return [$languageCode, $this->importTerm($name, $languageCode, $externalTerm)];
            // Creates an associative array with language code as key and term id as value
        })->toAssoc()->rejectNullValues()->toArray();
        LanguageProvider::saveTermTranslations($termIdsByLocale);
        return $termIdsByLocale;
    }

    public function deleteTerm(\WP_Term $term)
    {
        $termLink = get_term_link($term->term_id, $this->taxonomy);
        $this->preparePostRedirects($term->term_id);
        $result = wp_delete_term($term->term_id, $this->taxonomy);
        $this->createPostRedirects();
        if (! is_wp_error($result)) {
            BonnierRedirect::addRedirect(
                parse_url($termLink, PHP_URL_PATH),
                '/', // Redirect deleted term to front page
                LanguageProvider::getTermLanguage($term->term_id),
                'term-delete',
                $term->term_id
            );
            return true;
        }
        return false;
    }

    protected function importTerm($name, $languageCode, $externalTerm)
    {
        $contentHubId = $externalTerm->content_hub_ids->{$languageCode};
        $parentTermId = $this->getParentTermId($languageCode, $externalTerm->parent ?? null);
        $taxonomy = isset($externalTerm->vocabulary) ?
            WpTaxonomy::get_taxonomy($externalTerm->vocabulary->content_hub_id) :
            $this->taxonomy;
        $this->setLocaleFilter($languageCode);
        $description = $externalTerm->description->{$languageCode} ?? null;

        $meta = [
            'meta_title' => $externalTerm->meta_title->{$languageCode} ?? null,
            'meta_description' => $externalTerm->meta_description->{$languageCode} ?? null,
            'body' => $externalTerm->body->{$languageCode} ?? null,
            'image_url' => $externalTerm->image_url->{$languageCode} ?? null,
            'internal' => $externalTerm->internal ?? false,
            'whitealbum_id' => $externalTerm->whitealbum_id->{$languageCode} ?? null,
        ];

        if ($existingTermId = WpTerm::id_from_contenthub_id($contentHubId)) {
            $this->preparePostRedirects($existingTermId);
            $this->prepareCategoryRedirects($existingTermId);
            // Term exists so we update it
            if (WpTerm::update(
                $existingTermId,
                $name,
                $languageCode,
                $contentHubId,
                $taxonomy,
                $parentTermId,
                $description,
                $meta
            )) {
                $this->createPostRedirects();
                $this->createCategoryRedirects();
                return true;
            }
            return false;
        }
        // Create new term
        return WpTerm::create(
            $name,
            $languageCode,
            $contentHubId,
            $taxonomy,
            $parentTermId,
            $description,
            $meta
        );
    }

    protected function getParentTermId($languageCode, $externalCategory)
    {
        if (! isset($externalCategory->name->{$languageCode})) {
            // Make sure we only create the parent term if a translation exists for the language of the child term
            return null;
        }
        if ($existingTermId = WpTerm::id_from_contenthub_id($externalCategory->content_hub_ids->{$languageCode})) {
            // Term already exists so no need to create it again
            return $existingTermId;
        }
        return $this->importTermAndLinkTranslations($externalCategory)[$languageCode] ?? null;
    }

    public function deleteTermAndTranslations($externalTerm)
    {
        collect($externalTerm->content_hub_ids)->each(function ($contentHubId) {
            if ($wpTermId = WpTerm::id_from_contenthub_id($contentHubId) ?? null) {
                $wpTerm = get_term($wpTermId);
                if ($wpTerm instanceof \WP_Term) {
                    $this->deleteTerm($wpTerm);
                }
            }
        });
    }

    protected function getWpTaxonomy($taxonomy)
    {
        $wpTaxonomy = collect([
            'category' => 'category',
            'tag'      => 'post_tag',
            'post_tag' => 'post_tag'
        ])
            ->merge(collect(WpTaxonomy::get_custom_taxonomies()->pluck('machine_name')->keyBy(function ($value) {
                return $value;
            })))
            ->get($taxonomy);

        if (! $wpTaxonomy) {
            throw new Exception(sprintf('Unsupported taxonomy: %s', $taxonomy));
        }
        return $wpTaxonomy;
    }

    private function preparePostRedirects($existingTermId)
    {
        if ($this->taxonomy === 'category' && $existingTerm = get_term($existingTermId)) {
            $this->permalinksToRedirect = collect(get_posts([
                'posts_per_page' => -1, // Get all posts that match our query
                'post_status' => 'publish',
                'post_type' => WpComposite::POST_TYPE,
                'tax_query' => [
                    [
                        'taxonomy' => $this->taxonomy,
                        'field'    => 'id',
                        'terms'    => $existingTermId
                    ]
                ]
            ]))->reduce(function ($out, \WP_Post $post) {
                $out[$post->ID] = get_post_permalink($post->ID);
                return $out;
            }, collect([]));
            return;
        }
        $this->permalinksToRedirect = collect();
    }

    private function prepareCategoryRedirects($existingTermId)
    {
        if ($this->taxonomy === 'category' && $term = get_term($existingTermId)) {
            $terms = array_merge([$term], get_categories(['child_of' => $existingTermId]));
            $this->categoryLinksToRedirect = collect($terms)
                ->reduce(function ($out, \WP_Term $term) {
                    $out[$term->term_id] = get_category_link($term->term_id);
                    return $out;
                }, collect([]));
            return;
        }
        $this->categoryLinksToRedirect = collect();
    }

    private function createPostRedirects()
    {
        $this->permalinksToRedirect->each(function ($oldPermalink, $postId) {
            // Leave name to avoid hitting term cache
            if (($oldParsedUrl = parse_url($oldPermalink)) &&
                $newPermalink = get_post_permalink($postId, $leaveName = true)
            ) {
                // Since we left the post_name we need to replace it in the permalink.
                $newPermalink = str_replace('%postname%', get_post($postId)->post_name, $newPermalink);
                if ($oldPermalink !== $newPermalink) {
                    $language = LanguageProvider::getPostLanguage($postId);
                    $from = $oldParsedUrl['path'];
                    $to = parse_url($newPermalink, PHP_URL_PATH);
                    if ($existingRedirect = BonnierRedirect::findRedirectFor($to, $language)) {
                        if ($existingRedirect->to === $from) {
                            // Reverse redirect exists so we remove it to create a new one
                            BonnierRedirect::remove($existingRedirect->id);
                        }
                    }
                    BonnierRedirect::createRedirect(
                        $from,
                        $to,
                        LanguageProvider::getPostLanguage($postId),
                        'category-slug-change',
                        $postId
                    );
                }
            }
        });
    }

    private function createCategoryRedirects()
    {
        $this->categoryLinksToRedirect->each(function ($oldUrl, $termId) {
            if (($oldLink = parse_url($oldUrl, PHP_URL_PATH)) &&
                $newlink = parse_url(get_category_link($termId), PHP_URL_PATH)
            ) {
                if ($oldLink !== $newlink) {
                    $language = LanguageProvider::getTermLanguage($termId);
                    if ($existingRedirect = BonnierRedirect::findRedirectFor($newlink, $language)) {
                        if ($existingRedirect->to === $oldLink) {
                            BonnierRedirect::remove($existingRedirect->id);
                        }
                    }
                    BonnierRedirect::createRedirect(
                        $oldLink,
                        $newlink,
                        $language,
                        'category-slug-change',
                        $termId
                    );
                }
            }
        });
    }

    private function setLocaleFilter($languageCode)
    {
        // Needed by Polylang to allow same term name in different languages
        $_POST['term_lang_choice'] = $languageCode;
        $this->currentLocale = collect(LanguageProvider::getSimpleLanguageList(['fields' => 'locale']))
            ->first(function ($locale) use ($languageCode) {
                return str_contains($locale, $languageCode);
            });
        // Needed by wordpress in order to generate the correct slug
        add_filter('locale', [$this, 'getLocaleForSanitizeTitle'], 100);
    }

    public function getLocaleForSanitizeTitle($locale)
    {
        return $this->currentLocale ?: $locale;
    }
}
