<?php

namespace zenmodedaemon\classes\sites;

use PHPHtmlParser\Dom;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Clustering\KMeans;

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
        $links = $dom->find('#' . $this->config->result_id . ' a');

        $svgs = $dom->find('svg');
        foreach ($svgs as $svg) {
            $svg->setAttribute('viewBox', '');
        }

        $content = '';
        $lens = [];
        foreach ($links as $link) {
            $data = [
                strlen($link),
                strlen($link->text()),
                count($link->getChildren()),
                count($link->getAttributes())
            ];
            $lens[(string) $link] = $data;
            //echo json_encode($data). $link->text().PHP_EOL;
            //if ($len < 100) {
                //echo $link->innerHtml() . PHP_EOL;
            //}
            $content .= '<p>' . $link . '</p>';
        }
        $kmeans = new KMeans(4);
        $res = $kmeans->cluster($lens);
        usort(
            $res,
            function ($a, $b) {
                return count($a) - count($b);
            }
        );

        return $this->getHtml(implode('<hr>', array_keys($res[2])));
    }
}
