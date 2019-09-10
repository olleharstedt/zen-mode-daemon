<?php

namespace zenmodedaemon\classes\sites;

use PHPHtmlParser\Dom;

class SearchEngineResultSite extends SiteBase
{
    /**
     * @return string
     */
    public function getContent(): string
    {
        $url = $this->url
            . $this->config->query
            . $this->get[$this->config->getQuery];

        $dom = new Dom();
        $dom->loadFromUrl($url);
        $links = $dom->find('#main a');

        $content = '';
        foreach ($links as $link) {
            $content .= '<p>' . $link . '</p>';
        }

        return $this->getHtml($content);
    }
}
