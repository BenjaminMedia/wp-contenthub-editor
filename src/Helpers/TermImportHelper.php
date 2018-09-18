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
            return [ $languageCode, $this->importTerm($name, $languageCode, $externalTerm) ];
            // Creates an associative array with language code as key and term id as value
        })->toAssoc()->rejectNullValues()->toArray();
        LanguageProvider::saveTermTranslations($termIdsByLocale);
        return $termIdsByLocale;
    }

    protected function importTerm($name, $languageCode, $externalTerm)
    {
        $contentHubId = $externalTerm->content_hub_ids->{$languageCode};
        $whitealbumId = $externalTerm->whitealbum_id->{$languageCode} ?? null;
        $parentTermId = $this->getParentTermId($languageCode, $externalTerm->parent ?? null);
        $taxonomy = isset($externalTerm->vocabulary) ?
            WpTaxonomy::get_taxonomy($externalTerm->vocabulary->content_hub_id) :
            $this->taxonomy;
        // Needed by Polylang to allow same term name in different languages
        $_POST['term_lang_choice'] = $languageCode;

        $description = $externalTerm->description->{$languageCode} ?? null;
        $internal = $externalTerm->internal ?? false;

        if ($existingTermId = WpTerm::id_from_contenthub_id($contentHubId)) {
            $this->preparePostRedirects($existingTermId);
            // Term exists so we update it
            if (WpTerm::update(
                $existingTermId,
                $name,
                $languageCode,
                $contentHubId,
                $taxonomy,
                $parentTermId,
                $description,
                $internal,
                $whitealbumId
            )) {
                $this->createPostRedirects();
                return true;
            }
            return false;
        }
        // Create new term
        return WpTerm::create($name, $languageCode, $contentHubId, $taxonomy, $parentTermId, $description, $internal, $whitealbumId);
    }

    protected function getParentTermId($languageCode, $externalCategory)
    {
        if (!isset($externalCategory->name->{$languageCode})) {
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
                $this->preparePostRedirects($wpTermId);
                wp_delete_term($wpTermId, $this->taxonomy);
                $this->createPostRedirects();
            }
        });
    }

    protected function getWpTaxonomy($taxonomy)
    {
        $wpTaxonomy = collect([
            'category' => 'category',
            'tag' => 'post_tag',
            'post_tag' => 'post_tag'
        ])->get($taxonomy);
        if (!$wpTaxonomy) {
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
                        'field' => 'id',
                        'terms' => $existingTermId
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
                    BonnierRedirect::createRedirect(
                        $oldParsedUrl['path'],
                        parse_url($newPermalink, PHP_URL_PATH),
                        LanguageProvider::getPostLanguage($postId),
                        'category-slug-change',
                        $postId
                    );
                }
            }
        });
    }
}
