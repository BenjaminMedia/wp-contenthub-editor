<?php

namespace Bonnier;

/**
 * Video Parser
 *
 * Parses URLs from major cloud video providers. Capable of returning
 * keys from various video embed and link urls to manipulate and
 * access videos in various ways.
 */
class VideoHelper
{
    const YOUTUBE = 'youtube';
    const VIMEO = 'vimeo';
    const TWENTYTHREE = "23video";

    /**
     * Determines which cloud video provider is being used based on the passed url.
     *
     * @param string $url The url
     * @return null|string Null on failure to match, the service's name on success
     */
    public static function identify_service($url)
    {
        if (preg_match('%youtube|youtu\.be%i', $url)) {
            return self::YOUTUBE;
        } elseif (preg_match('%vimeo%i', $url)) {
            return self::VIMEO;
        } elseif (preg_match('%23video%', $url)) {
            return self::TWENTYTHREE;
        }
        return null;
    }


    /**
     * Determines which cloud video provider is being used based on the passed url,
     * and extracts the video id from the url.
     *
     * @param string $url The url
     * @return null|string Null on failure, the video's id on success
     */
    public static function get_url_id($url)
    {
        $service = self::identify_service($url);
        //TODO use a function for this, it is duplicated
        if ($service == self::YOUTUBE) {
            return self::get_youtube_id($url);
        } elseif ($service == self::VIMEO) {
            return self::get_vimeo_id($url);
        } elseif ($service == self::TWENTYTHREE) {
            return self::get_vimeo_id($url);
        }
        return null;
    }

    /**
     * Determines which cloud video provider is being used based on the passed url,
     * extracts the video id from the url, and builds an embed url.
     *
     * @param string $url The url
     * @return null|string Null on failure, the video's embed url on success
     */
    public static function get_url_embed($url)
    {
        $service = self::identify_service($url);

        $id = self::get_url_id($url);

        if ($service == self::YOUTUBE) {
            return self::get_youtube_embed($id);
        } elseif ($service == self::VIMEO) {
            return self::get_vimeo_embed($id);
        } elseif ($service == self::TWENTYTHREE) {
            return self::get_vimeo_id($id);
        }
        return null;
    }

    /**
     * Parses various youtube urls and returns video identifier.
     *
     * @param string $url The url
     * @return string the url's id
     */
    public static function get_youtube_id($url)
    {
        $youtube_url_keys = array('v','vi');

        // Try to get ID from url parameters
        $key_from_params = self::parse_url_for_params($url, $youtube_url_keys);
        if ($key_from_params) {
            return $key_from_params;
        }

        // Try to get ID from last portion of url
        return self::parse_url_for_last_element($url);
    }

    /**
     * Builds a Youtube embed url from a video id.
     *
     * @param string $youtube_video_id The video's id
     * @return string the embed url
     */
    public static function get_youtube_embed($youtube_video_id, $autoplay = 1)
    {
        //TODO remove HTTP ?
        $embed = "http://youtube.com/embed/$youtube_video_id?autoplay=$autoplay";

        return $embed;
    }

    /**
     * Parses various vimeo urls and returns video identifier.
     *
     * @param string $url The url
     * @return string The url's id
     */
    public static function get_vimeo_id($url)
    {
        // Try to get ID from last portion of url
        return self::parse_url_for_last_element($url);
    }

    /**
     * Builds a Vimeo embed url from a video id.
     *
     * @param string $vimeo_video_id The video's id
     * @return string the embed url
     */
    public static function get_vimeo_embed($vimeo_video_id, $autoplay = 1)
    {
        $embed = "http://player.vimeo.com/video/$vimeo_video_id?byline=0&amp;portrait=0&amp;autoplay=$autoplay";

        return $embed;
    }

    /**
     * Find the first matching parameter value in a url from the passed params array.
     *
     * @access private
     *
     * @param string $url The url
     * @param array $target_params Any parameter keys that may contain the id
     * @return null|string Null on failure to match a target param, the url's id on success
     */
    private static function parse_url_for_params($url, $target_params)
    {
        parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_params);
        foreach ($target_params as $target) {
            if (array_key_exists($target, $my_array_of_params)) {
                return $my_array_of_params[$target];
            }
        }
        return null;
    }

    /**
     * Find the last element in a url, without any trailing parameters
     *
     * @access private
     *
     * @param string $url The url
     * @return string The last element of the url
     */
    private static function parse_url_for_last_element($url)
    {
        //TODO change to:
        //$path = parse_url($url, PHP_URL_PATH);
        // basename($path);
        $url_parts = explode("/", $url);
        $prospect = end($url_parts);
        $prospect_and_params = preg_split("/(\?|\=|\&)/", $prospect);
        if ($prospect_and_params) {
            return $prospect_and_params[0];
        } else {
            return $prospect;
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getVimeoThumb($id)
    {
        $vimeo = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
        if (isset($vimeo[0])) {
            return $vimeo[0]['thumbnail_large'];
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function getYoutubeThumb($id)
    {
        if (!empty($id)) {
            return self::getBestYoutubeThumb($id);
        }

        return false;
    }

    /**
     * @param $id
     * @param string $youtubeHost
     * @return bool|mixed
     */
    public static function getBestYoutubeThumb($id, $youtubeHost = "https://img.youtube.com/vi/")
    {
        $thumbResolutions = array(
            //1920x1080
            'maxQualityThumb' => $youtubeHost.$id."/maxresdefault.jpg",
            //640x480
            'standardQualityThumb' => $youtubeHost.$id."/sddefault.jpg",
            //480x360
            'highQualityThumb' => $youtubeHost.$id."/hqdefault.jpg",
            //480x360 the first thumb that we can possibly get. Player Background Thumbnail
            'playerBackgroundThumb' => $youtubeHost.$id."/0.jpg"
        );

        foreach($thumbResolutions as $thumbUrl)
        {
            //If thumb doesn't exist we will get a 404
            if(@file_get_contents( urldecode($thumbUrl) )){
                return $thumbUrl;
            }
        }

        return false;
    }

    /**
     * @param $token
     * @param $photoId
     * @param $host
     * @return bool|string
     */
    public static function getTwentyThreeThumb($token, $photoId, $host)
    {
        if (!empty($token) && !empty($photoId) && !empty($host)) {

            $api_request    = $host.'/api/photo/list?token='.$token.'&photo_id='.$photoId.'&format=xml';

            try{
                //TODO Replace wp_remote_retrieve_body && wp_remote_get to remove wp requirement.
                $xmlResponse = wp_remote_retrieve_body( wp_remote_get( $api_request ) );
                $xml = simplexml_load_string($xmlResponse);
            }
            catch(\Exception $e)
            {
                throw new \Exception("Request Error: ".$e);
            }

            if(empty(self::xml_attribute($xml->photo, 'large_download') ?? ''))
            {
                return false;
            }

            return $host.self::xml_attribute($xml->photo, 'large_download').'/thumbnail.jpg';
        }

        return false;
    }

    /**
     * @param $embed
     * @return bool
     */
    public static function getTwentyThreeToken($embed)
    {
        preg_match("/\?token=(.*?)&/", $embed, $matches);

        if(isset($matches[1]))
        {
            return $matches[1];
        }

        return false;
    }

    /**
     * @param $embed
     * @return bool
     */
    public static function getTwentyThreePhotoId($embed)
    {
        preg_match("/id=(.*)/", $embed, $matches);

        if(isset($matches[1]))
        {
            return $matches[1];
        }

        return false;
    }

    /**
     * @param $embed
     * @param null $host
     * @return array|bool|object
     */
    public static function getLeadImageFile($embed, $host = null)
    {
        $url = self::getEmbedCode($embed);

        if(!$url)
        {
            return false;
        }

        $provider = VideoHelper::identify_service($url);

        switch ($provider) {
            case VideoHelper::YOUTUBE:
                $videoId = VideoHelper::get_youtube_id($url);
                $thumb = VideoHelper::getYoutubeThumb($videoId);
                break;
            case VideoHelper::VIMEO:
                $videoId = VideoHelper::get_vimeo_id($url);
                $thumb = VideoHelper::getVimeoThumb($videoId);
                break;
            case VideoHelper::TWENTYTHREE:
                $token = VideoHelper::getTwentyThreeToken($url);
                $photoId = VideoHelper::getTwentyThreePhotoId($url);
                //This will be used as file id
                $videoId = $token.$photoId;

                $thumb = VideoHelper::getTwentyThreeThumb($token, $photoId, $host);
                break;
            default:
                return array();
        }

        return (object) array('id' => $provider.'_'.md5($videoId), 'url'=> $thumb, 'provider' => $provider);
    }

    /**
     * @param $embed
     * @return bool|mixed
     */
    public static function getEmbedCode($embed)
    {
        preg_match('/src="(.*?)"/', $embed, $matches);

        if(!isset($matches[1]))
        {
            return false;
        }

        if (substr($matches[1], 0, 2) == '//') {
            $matches[1] = 'https:' . $matches[1];
        }

        return filter_var($matches[1], FILTER_VALIDATE_URL);
    }

    /**
     * @param $object
     * @param $attribute
     * @return string
     */
    public static function xml_attribute($object, $attribute)
    {
        if(isset($object[$attribute]))
            return (string) $object[$attribute];
    }
}
