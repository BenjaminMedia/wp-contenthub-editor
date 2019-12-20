<?php


namespace Bonnier\WP\ContentHub\Editor\Rss;

use Bhaktaraz\RSSGenerator\Feed;
use Bhaktaraz\RSSGenerator\SimpleXMLElement;
use DOMDocument;

class MsnFeed extends Feed
{
    /**
     * Overwrite parent function for adding xmlns:dcterms
     * @return string
     */
    public function render()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><rss xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:media="http://search.yahoo.com/mrss/"
    xmlns:mi="http://schemas.ingestion.microsoft.com/common/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:dcterms="http://purl.org/dc/terms/"
    version="2.0">',
            LIBXML_NOERROR | LIBXML_ERR_NONE | LIBXML_ERR_FATAL);

        foreach ($this->channels as $channel) {
            $toDom = dom_import_simplexml($xml);
            $fromDom = dom_import_simplexml($channel->asXML());
            $toDom->appendChild($toDom->ownerDocument->importNode($fromDom, true));
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->appendChild($dom->importNode(dom_import_simplexml($xml), true));
        $dom->formatOutput = true;

        return $dom->saveXML();
    }
}
