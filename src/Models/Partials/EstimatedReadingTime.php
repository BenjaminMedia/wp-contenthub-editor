<?php

namespace Bonnier\WP\ContentHub\Editor\Models\Partials;

/**
 * Class EstimatedReadingTime
 *
 * @package \Bonnier\WP\ContentHub\Editor\Models\Partials
 */
class EstimatedReadingTime
{
    const EXTENDED_CHARLIST = 'âÂéÉèÈêÊøØóÓòÒôÔäÄåÅöÖæÆ:/.';

    public static function addEstimatedReadingTime($postId)
    {
        list($totalWordCount, $imageCounter) = static::getWordAndImageCount($postId);

        $locale = pll_get_post_language($postId);

        $readingTimeInSecconds = static::readingTime($locale, $totalWordCount) + self::imageConsumationTime($imageCounter);

        $readingTimeInMinutes = ceil( // Round to nearest whole minute using ceil to avoid hitting 0
            $readingTimeInSecconds / 60
        );

        update_post_meta($postId, 'word_count', $totalWordCount);
        update_post_meta($postId, 'reading_time', $readingTimeInMinutes);
    }

    private static function imageConsumationTime($amountOfImages)
    {
        $defaultConsumptionTime = 12;
        if ($amountOfImages <= 10) {
            $seconds = $defaultConsumptionTime * $amountOfImages;
        } else {
            $seconds = ($defaultConsumptionTime * 10) + (($amountOfImages - 10) * 3);
        }
        return $seconds;
    }

    private static function getWordAndImageCount($postId)
    {
        $totalWordCount = 0;
        $imageCounter = 0;

        foreach (get_field('composite_content', $postId) as $contentWidget) {
            switch ($contentWidget['acf_fc_layout']) {
                case 'gallery':
                    $imageCounter += count($contentWidget['images']);
                    break;
                case 'image':
                    $imageCounter++;
                    break;
                case 'text_item':
                case 'infobox':
                    $totalWordCount += str_word_count($contentWidget['body'], 0, self::EXTENDED_CHARLIST);
                    break;
                case 'lead_paragraph':
                    $totalWordCount += str_word_count($contentWidget['title'] . $contentWidget['description'], 0, self::EXTENDED_CHARLIST);
                    break;
                case 'paragraph_list':
                    $totalWordCount += self::getParagraphListWordCount($contentWidget, $imageCounter);
                    break;
                case 'hotspot_image':
                    $totalWordCount += self::getHostspotImageWordCount($contentWidget, $imageCounter);
                    break;
                case 'inserted_code':
                case 'link':
                case 'video':
                case 'file':
                default:
                    break;
            }
        }

        return [$totalWordCount, $imageCounter];
    }

    private static function readingTime($locale, $wordCount)
    {
        $wordsPerMinute = 180;
        if ($locale === 'fi') {
            $wordsPerMinute = 150;
        }
        // calculcate number of minutes required to read number of words and convert to secconds
        return $wordCount / $wordsPerMinute * 60;
    }

    private static function getParagraphListWordCount($contentWidget, int &$imageCounter)
    {
        if ($contentWidget['image']) {
            $imageCounter++;
        }
        $widgetWords = $contentWidget['title'] . $contentWidget['description'];
        $widgetWords .= array_reduce($contentWidget['items'], function ($words, $paragraphItem) use (&$imageCounter) {
            $words .= $paragraphItem['title'] . $paragraphItem['description'];
            if ($paragraphItem['image']) {
                $imageCounter++;
            }
            return $words;
        }, '');
        return str_word_count($widgetWords, 0, self::EXTENDED_CHARLIST);
    }

    private static function getHostspotImageWordCount($contentWidget, int &$imageCounter)
    {
        if ($contentWidget['image']) {
            $imageCounter++;
        }
        $widgetWords = $contentWidget['title'] . $contentWidget['description'];
        $widgetWords .= array_reduce($contentWidget['hotspots'], function ($words, $hotspotItem) use (&$imageCounter) {
            $words .= $hotspotItem['title'] . $hotspotItem['description'];
            return $words;
        }, '');
        return str_word_count($widgetWords, 0, self::EXTENDED_CHARLIST);
    }
}
