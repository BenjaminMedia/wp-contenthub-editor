<?php

namespace Bonnier\WP\ContentHub\Editor\Rss;

use App\Enum\AcfName;
use App\Helpers\SortByHelper;
use App\Models\Views\CompositeTeaser;
use Bhaktaraz\RSSGenerator\Channel;
use Bhaktaraz\RSSGenerator\Feed;
use Bonnier\WP\ContentHub\Editor\Models\ACF\Composite\CompositeFieldGroup;
use Bonnier\WP\ContentHub\Editor\Models\WpAttachment;
use Bonnier\WP\ContentHub\Editor\Models\WpComposite;
use Bonnier\WP\ContentHub\Editor\Singleton;
use Illuminate\Support\Collection;
use Parsedown;
use WP_Post;
use WP_Term;

class FeedRss
{
    /**
     * @var Feed $feed
     */
    private $feed;

    /**
     * @var Channel $channel
     */
    private $channel;

    use Singleton;

    public static function register()
    {
        remove_action('do_feed_rss2', 'do_feed_rss2', 10);
        add_action('do_feed_rss2', [self::getInstance(), 'generateOutput']);
        add_action('languages_page_mlang_strings',function (){
            pll_register_string('RSS - Read More', 'Læs mere', 'contenthub-editor-rss');
        });
    }

    public function __construct()
    {
        $this->feed = new MsnFeed();
        $this->channel = new Channel();
    }

    public function makeChannel()
    {
        $frontPageId = get_option('page_on_front');
        $homeLink = get_permalink($frontPageId);
        $this->channel
            ->title(wp_get_document_title())
            ->description(get_post_meta($frontPageId, '_yoast_wpseo_metadesc', true))
            ->atomLinkSelf($homeLink)
            ->url($homeLink)
            ->language(get_bloginfo_rss('language'))
            ->appendTo($this->feed);
    }

    /**
     * Generate final MSN Rss feed output
     */
    public function generateOutput()
    {
        $this->makeChannel();
        $this->getPosts()->each(function (WP_Post $composite) {
            $this->makeItem($composite);
        });

        header('Content-Type: Application/xml');
        echo $this->feed;
//         wp_die() is not working here, because it adds debug message at end of rss result.
        die();
    }

    /**
     * Generate a article post item
     * @param WP_Post $composite
     */
    protected function makeItem(WP_Post $composite)
    {
        $postDate = $composite->post_date ?? '';
        $modifiedDate = $composite->post_modified ?? '';
        $permalink = get_permalink($composite->ID) ?? '';
        $authorId = $composite->post_author;

        $item = new MsnFeedItem();
        $fieldsCol = collect(get_fields($composite->ID))->recursive();
        $description = $fieldsCol->get('description', '');
        /**
         * @var $category WP_Term
         */
        $category = $fieldsCol->get('category', false);
        $categoryName = $category ? $category->name : '';
        $content = $this->makeContent($fieldsCol);

        $item
            ->title($composite->post_title)
            ->guid($composite->ID)
//            pubDate element is required, otherwise it will throw exception with 'missing version' error in https://feeds.msn.com/evaluation V3
            ->pubDate(strtotime($postDate))
            ->modifiedDate(strtotime($modifiedDate))
            ->url($permalink)
            ->creator(get_the_author_meta('display_name', $authorId))
            ->category($categoryName)
            ->description($description)
            ->content($content)
            ->appendTo($this->channel);
    }

    /**
     * Generate content for a article item
     * @param Collection $fieldsCol
     * @return string
     */
    protected function makeContent(Collection $fieldsCol): string
    {
        $contentHtml = '';
        $teaserImageId = $fieldsCol->get('teaser_image', false);
        $contentHtml .= $this->wrapImageHtml($teaserImageId);
        $fieldsCol->get('composite_content')->each(function ($element) use (&$contentHtml) {
            /**
             * @var Collection $element
             */
            switch ($element->get('acf_fc_layout')) {
                /**
                 * acf_fc_layout types in class @var CompositeFieldGroup
                 */
                case "lead_paragraph":
                    $contentHtml .= Parsedown::instance()->text($element->get('title', ''));
                    $contentHtml .= Parsedown::instance()->text($element->get('description', ''));
                    break;
                case "text_item":
                    $contentHtml .= Parsedown::instance()->text($element->get('body', ''));
                    break;
                case "image":
                    $contentHtml .= $this->wrapImageHtml($element->get('file', ''));
                    break;
                case "video":
                    $contentHtml .= $element->get('embed_url', '');
                    break;
            }
        });
        $category = $fieldsCol->get('category', '');
        $readMoreArticles = $this->getLatestArticles($category, 3);
        $readMoreArticles->each(function (CompositeTeaser $compositeTeaser) use (&$contentHtml) {
            $title = $compositeTeaser->getTitle();
            $uri = $compositeTeaser->getUri();
            $contentHtml .= pll__('Læs mere') . ': ' . sprintf('<a target="_blank" href="%s">%s</a></br>', $uri, $title);
        });

        return $contentHtml;
    }

    /**
     * Check if image has copyright. Image from 'shutterstock' treats as a free (no copyright) image.
     * @param $teaserImageId
     * @return bool
     */
    public function imageHasCopyright($teaserImageId): bool
    {
        $attachmentMetaData = get_post_meta($teaserImageId) ?? [];
        $copyrightArr = $attachmentMetaData[WpAttachment::POST_META_COPYRIGHT] ?? false;

        if ($copyrightArr) {
            //containing keyword: shutterstock, means no copyright
            return !(stripos($copyrightArr[0], 'shutterstock') !== false);
        }
        return false;
    }

    /**
     * @return Collection
     */
    protected function getPosts(): Collection
    {
        $args = [
            'post_type' => WpComposite::POST_TYPE,
            'posts_per_page' => 20,
            'paged' => 0,
        ];

        return collect(get_posts($args));
    }

    protected function getLatestArticles($category, $amountOfTeasers)
    {
        $widget = [
            AcfName::WIDGET_TEASER_AMOUNT => $amountOfTeasers,
            AcfName::WIDGET_CATEGORY => $category,
            AcfName::WIDGET_SORT_BY => AcfName::WIDGET_SORT_BY_LATEST
        ];

        return SortByHelper::getSortedWidgetTeasers($widget, $widget[AcfName::WIDGET_TEASER_AMOUNT]);
    }

    /**
     * Wrap image html and check image copyright
     * @param $imageId
     * @return string
     */
    protected function wrapImageHtml($imageId): string
    {
        if ($imageId && !$this->imageHasCopyright($imageId)) {
            $imageUrl = wp_get_attachment_image_url($imageId, ['500', '500']);
            if ($imageUrl) {
                $title = get_the_title($imageId);
                return sprintf('<img src="%s"  alt="%s" title="%s"/> <br/>', $imageUrl, $title, $title);
            }
        }
        return '';
    }
}
