<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Bonnier\Willow\Base\Adapters\Wp\Composites\CompositeAdapter;
use Bonnier\Willow\Base\Models\Base\Composites\Composite;
use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\Cxense\Parsers\Document;
use Bonnier\WP\Cxense\Services\WidgetDocumentQuery;
use Illuminate\Support\Collection;

class SortBy
{
    const MANUAL = 'manual';
    const CUSTOM = 'custom';
    const POPULAR = 'popular';
    const RECENTLY_VIEWED = 'recently_viewed';
    const CXENSE_TREND = 'trend';
    const CXENSE_RECENT = 'recent';

    private static $acfWidget;

    /**
     * Get a collection of composites based on an ACF Widget
     *
     * @param array $acfWidget
     * @return Collection|null A collection of composites
     */
    public static function getComposites(array $acfWidget): ?Collection
    {
        self::$acfWidget = $acfWidget;

        $method = collect([
            self::MANUAL => 'getManualComposites',
            self::CUSTOM => 'getCompositesByTaxonomy',
            self::POPULAR => 'getPopularComposites',
            self::RECENTLY_VIEWED => 'getRecentlyViewedComposites',
        ])->get(self::$acfWidget[AcfName::FIELD_SORT_BY] ?? null);

        return $method ? self::$method() : null;
    }

    /**
     * Get a collection of manually selected composites.
     *
     * @return Collection|null A collection of composites
     */
    protected static function getManualComposites(): ?Collection
    {
        if ($teasers = self::$acfWidget['teaser_list'] ?? null) {
            return collect($teasers)->map(function (\WP_Post $teaser) {
                return new Composite(new CompositeAdapter($teaser));
            });
        }

        return null;
    }

    /**
     * Get a collection of latest published composites
     * by category, tags and custom taxonomy.
     *
     * @return Collection|null A collection of composites
     */
    protected static function getCompositesByTaxonomy(): ?Collection
    {
        $args = [
            'posts_per_page' => self::$acfWidget['teaser_amount'] ?? 4,
            'order' => 'DESC',
            'orderby' => 'post_date',
            'post_type' =>  WpComposite::POST_TYPE,
            'post_status' => 'publish',
            'suppress_filters' => true,
            'update_post_term_cache' => false, // disable fetching of terms, and a write query to the database
            'cache_results' => false,
            'lang' => LanguageProvider::getCurrentLanguage(),
            'fields' => 'ids',
        ];

        /** @var Collection $taxonomies */
        $taxonomies = collect([
            self::isWpTerm(self::$acfWidget[AcfName::FIELD_CATEGORY]) ? self::$acfWidget[AcfName::FIELD_CATEGORY] :null,
            self::isWpTerm(self::$acfWidget[AcfName::FIELD_TAG]) ? self::$acfWidget[AcfName::FIELD_TAG] : null,
        ])->rejectNullValues();

        $customTaxonomies = WpTaxonomy::get_custom_taxonomies()->map(function ($taxonomy) {
            if (($term = self::$acfWidget[$taxonomy->machine_name] ?? null) && self::isWpTerm($term)) {
                return $term;
            }
            return null;
        })->rejectNullValues();

        $args['tax_query'] = $taxonomies->merge($customTaxonomies)->map(function (\WP_Term $term) {
            return [
                'taxonomy' => $term->taxonomy,
                'field' => 'term_id',
                'terms' => $term->term_id,
            ];
        })->values()->toArray();

        $teaserQuery = new \WP_Query($args);

        if ($teaserQuery->have_posts()) {
            return collect($teaserQuery->posts)->map(function ($postId) {
                if ($composite = self::getPost($postId)) {
                    return new Composite(new CompositeAdapter($composite));
                }
                return null;
            })->rejectNullValues();
        }

        return null;
    }

    /**
     * Get a collection of popular composites from cxense
     * optionally based on category and tags.
     *
     * @return Collection|null A collection of composites
     */
    protected static function getPopularComposites(): ?Collection
    {
        $query = WidgetDocumentQuery::make()->byPopular();
        if (self::isWpTerm(self::$acfWidget[AcfName::FIELD_CATEGORY] ?? null)) {
            $query->setCategories([self::$acfWidget[AcfName::FIELD_CATEGORY]]);
        }

        if (self::isWpTerm(self::$acfWidget[AcfName::FIELD_TAG] ?? null)) {
            $query->setTags([self::$acfWidget[AcfName::FIELD_TAG]]);
        }

        $result = $query->get();

        return self::convertCxenseResultToComposites($result);
    }

    /**
     * Get a collection of recently viewed composites from cxense.
     *
     * @return Collection|null A collection of composites
     */
    protected static function getRecentlyViewedComposites(): ?Collection
    {
        $result = WidgetDocumentQuery::make()->byRecentlyViewed()->get();

        return self::convertCxenseResultToComposites($result);
    }

    /**
     * Validate that the given param is an instance of WP_Term
     *
     * @param $term
     * @return bool
     */
    private static function isWpTerm($term)
    {
        return !is_null($term) && $term instanceof \WP_Term;
    }

    /**
     * Will convert a post ID to a WP_Post,
     * only if the post is published and is a contenthub composite.
     *
     * @param int|null $postId Post ID to look up.
     * @return null|\WP_Post The composite post.
     */
    private static function getPost(?int $postId): ?\WP_Post
    {
        if (!$postId) {
            return null;
        }
        if ($post = get_post($postId)) {
            if ($post->post_type === WpComposite::POST_TYPE && $post->post_status === 'publish') {
                return $post;
            }
        }

        return null;
    }

    /**
     * Convert the result array from a cxense query to a collection of composites.
     *
     * @param array $result Result from Cxense Query
     * @return Collection|null A collection of composites or null.
     */
    private static function convertCxenseResultToComposites($result): ?Collection
    {
        return collect($result['matches'])->map(function (Document $cxArticle) {
            if ($post = self::getPost(data_get($cxArticle, 'recs-articleid'))) {
                return new Composite(new CompositeAdapter($post));
            }
            return null;
        })->rejectNullValues();
    }
}
