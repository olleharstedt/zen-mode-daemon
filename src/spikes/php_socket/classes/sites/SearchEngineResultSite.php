<?php

namespace zenmodedaemon\classes\sites;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\AbstractNode;
use PHPHtmlParser\Dom\TextNode;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Clustering\KMeans;
use Phpml\Clustering\DBSCAN;

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
        $links = $dom->find('a');
        var_dump(count($links));

        $svgs = $dom->find('svg');
        foreach ($svgs as $svg) {
            $svg->setAttribute('viewBox', '');
        }

        $content = '';
        $lens = [];
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $data = [
                strlen($link->text()),
                $this->countAllChildren($link),
                substr($href, 0, 1) === '/' ? 0 : 1
            ];
            $lens[(string) $link] = $data;
            //echo json_encode($data). $link->text().PHP_EOL;
            //if ($len < 100) {
                //echo $link->innerHtml() . PHP_EOL;
            //}
            $content .= '<p>' . $link . '</p>';
        }
        /*
        $kmeans = new KMeans(6);
        $res = $kmeans->cluster($lens);
         */
        $dbscan = new DBSCAN($epsilon = 5, $minSamples = 3);
        $res = $dbscan->cluster($lens);
        usort(
            $res,
            function ($a, $b) {
                return count($a) - count($b);
            }
        );
        var_dump(count($res));

        $html = '';
        foreach ($res as $i => $re) {
            $html .= "<h2>$i</h2>";
            $html .= implode('<hr>', array_keys($res[$i]));
        }

        return $this->getHtml($html);
    }

    /**
     * @return int
     */
    protected function countAllChildren(AbstractNode $node): int
    {
        $res = 0;
        foreach ($node->getChildren() as $child) {
            if ($child instanceof TextNode) {
                $res += 1;
            } else {
                $res += 1 + $this->countAllChildren($child);
            }
        }
        return $res;
    }
}
