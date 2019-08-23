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
        $seconds = 0;
        $defaultConsumeTime = 12;

        if ($amountOfImages <= 10) {
            $seconds = $defaultConsumptionTime * $amountOfImages;
        } else {
            $seconds = ($defaultConsumptionTime * 10) + (($amountOfImages - 10) * 3)
        }
            if ($i < 10) {
                $seconds = $seconds + ($defaultConsumeTime - $i);
            } else {
                // After 10 images the average time pr images is estimated to 3 sec. (source Medium)
                $seconds = $seconds + 3;
            }
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
                    $totalWordCount = $totalWordCount + str_word_count($contentWidget['body'], 0, self::EXTENDED_CHARLIST);
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
        return $wordCount / $wordsPerMinute * 60;
            case 'fi':
                $wordsPerMinute = 150;
                break;
            default:
                $wordsPerMinute = 180;
                break;
        }

        // calculcate number of minutes required to read number of words and convert to secconds
        return $wordCount / $wordsPerMinute * 60;
    }
}
