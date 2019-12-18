<?php


namespace Bonnier\WP\ContentHub\Editor\Rss;

use Bhaktaraz\RSSGenerator\Item;
use Bhaktaraz\RSSGenerator\SimpleXMLElement;

class MsnFeedItem extends Item
{
    /** @var string */
    protected $modifiedDate;

    /**
     * @param string $modifiedDate
     * @return $this
     */
    public function modifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
        return $this;
    }

    /**
     * @return SimpleXMLElement
     */
    public function asXML()
    {
        $xml = parent::asXML();
        if ($this->modifiedDate !== null) {
            $xml->addChild('xsi:dcterms:modified', date(DATE_RSS, $this->modifiedDate));
        }

        return $xml;
    }
}