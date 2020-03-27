<?php

namespace Bonnier\WP\ContentHub\Editor\Helpers;

use Bonnier\Willow\MuPlugins\Helpers\LanguageProvider;
use Bonnier\WP\ContentHub\Editor\Models\FeatureDate;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Models\WpTaxonomy;
use Bonnier\WP\Cxense\Parsers\Document;
use Bonnier\WP\Cxense\Services\WidgetDocumentQuery;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SortBy
{
    const MANUAL = 'manual';
    const CUSTOM = 'custom';
    const POPULAR = 'popular';
    const RECENTLY_VIEWED = 'recently_viewed';
    const SHUFFLE = 'shuffle';
    const CXENSE_TREND = 'trend';
    const CXENSE_RECENT = 'recent';

    private static $acfWidget;
    private static $page;

    /**
     * Get a collection of composites based on an ACF Widget
     *
     * @param array $acfWidget
     * @param int $page
     *
     * @return Collection|null A collection of composites
     */
    public static function getComposites(array $acfWidget, int $page = 1): ?array
    {
        self::$acfWidget = $acfWidget;
        self::$page = $page;

        $method = collect([
            self::MANUAL => 'getManualComposites',
            self::CUSTOM => 'getCompositesByTaxonomy',
            self::POPULAR => 'getPopularComposites',
            self::RECENTLY_VIEWED => 'getRecentlyViewedComposites',
            self::SHUFFLE => 'getShuffleComposites',
        ])->get(self::$acfWidget[AcfName::FIELD_SORT_BY] ?? null);

        return $method ? self::$method() : null;
    }

    /**
     * Get a collection of manually selected composites.
     *
     * @return array|null A collection of composites
     */
    protected static function getManualComposites(): ?array
    {
        if ($teasers = self::$acfWidget['teaser_list'] ?? null) {
            $count = count($teasers);
            return [
                'composites' => collect($teasers),
                'page' => 1,
                'per_page' => $count,
                'total' => $count,
                'pages' => 1,
            ];
        }

        return null;
    }

    /**
     * Get a collection of latest published composites
     * by category, tags and custom taxonomy.
     *
     * @return array|null A collection of composites
     */
    protected static function getCompositesByTaxonomy(): ?array
    {
        $currentLanguage = LanguageProvider::getCurrentLanguage();
        $featuredPostIdTimestamps = FeatureDate::where('timestamp', '<', Carbon::now())
            ->orderBy('timestamp', 'desc')
            ->get()
            ->pluck('timestamp', 'post_id')
            ->filter(function ($value, $postID) use ($currentLanguage) {
                return LanguageProvider::getPostLanguage($postID) == $currentLanguage;
            })
            ->toArray();
        $featuredPostIds =  array_keys($featuredPostIdTimestamps);
        $args = [
            'posts_per_page' => self::$acfWidget['teaser_amount'] ?? 4,
            'paged' => self::$page,
            'order' => 'DESC',
            'orderby' => 'post_date',
            'post__not_in' => $featuredPostIds,
            'post_type' =>  WpComposite::POST_TYPE,
            'post_status' => 'publish',
            'suppress_filters' => true,
            'update_post_term_cache' => false, // disable fetching of terms, and a write query to the database
            'cache_results' => false,
            'lang' => $currentLanguage,
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
        if (self::$page === 1) {
            $featuredPosts = collect(get_posts([
                'posts_per_page' => $args['posts_per_page'],
                'post_type' => WpComposite::POST_TYPE,
                'post__in' => $featuredPostIds,
                'orderby' => 'post__in',
                'tax_query' => $args['tax_query']
            ]));
        } else {
            // If we are paginating, we'll ignore the featured posts.
            $featuredPosts = collect();
        }

        $latestPosts = $featuredPosts->merge(collect($teaserQuery->posts))
                               ->sortByDesc(function ($post) use ($featuredPostIdTimestamps) {
                                   $featuredPost = $featuredPostIdTimestamps[$post->ID] ?? false;
                                   return $featuredPost ? $featuredPost->getTimestamp() : strtotime($post->post_date);
                               })->take($args['posts_per_page']);

        if ($teaserQuery->have_posts()) {
            return [
                'composites' => $latestPosts,
                'page' => self::$page,
                'per_page' => intval($args['posts_per_page']),
                'total' => intval($teaserQuery->found_posts),
                'pages' => intval($teaserQuery->max_num_pages),
            ];
        }

        return null;
    }

    /**
     * Get a collection of popular composites from cxense
     * optionally based on category and tags.
     *
     * @return array|null A collection of composites
     */
    public static function getPopularComposites(): ?array
    {
        $query = WidgetDocumentQuery::make()->byPopular();
        if (self::isWpTerm(self::$acfWidget[AcfName::FIELD_CATEGORY] ?? null)) {
            $query->setCategories([self::$acfWidget[AcfName::FIELD_CATEGORY]]);
        }

        if (self::isWpTerm(self::$acfWidget[AcfName::FIELD_TAG] ?? null)) {
            $query->setTags([self::$acfWidget[AcfName::FIELD_TAG]]);
        }

        $result = $query->get();

        return self::convertCxenseResultToComposites($result, self::$acfWidget['teaser_amount']);
    }

    /**
     * Get a collection of recently viewed composites from cxense.
     *
     * @return array|null A collection of composites
     */
    public static function getRecentlyViewedComposites(): ?array
    {
        $result = WidgetDocumentQuery::make()->byRecentlyViewed()->get();

        return self::convertCxenseResultToComposites($result, self::$acfWidget['teaser_amount']);
    }

    /**
     * Get a collection of shuffled composites from cxense.
     *
     * @return array|null A collection of composites
     */
    public static function getShuffleComposites(): ?array
    {
        $result = WidgetDocumentQuery::make()->byShuffle()->get();

        return self::convertCxenseResultToComposites($result, self::$acfWidget['teaser_amount']);
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
     * @param $count
     *
     * @return array|null A collection of composites or null.
     */
    private static function convertCxenseResultToComposites($result, $count): ?array
    {
        if (!array_get($result, 'matches')) {
            $result['matches'] = [];
        }
        return [
            'composites' => collect($result['matches'])->map(function (Document $cxArticle) {
                if (($postId = self::getPost($cxArticle->{'recs-articleid'})) && $post = get_post($postId)) {
                    return $post;
                }
                return null;
            })->rejectNullValues()->slice(0, $count),
            'page' => 1,
            'per_page' => $count,
            'total' => $count,
            'pages' => 1,
        ];
    }
}
