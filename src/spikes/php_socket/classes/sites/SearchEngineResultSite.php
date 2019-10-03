<?php

namespace zenmodedaemon\classes\sites;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\AbstractNode;
use PHPHtmlParser\Dom\TextNode;
use PHPHtmlParser\Exceptions\EmptyCollectionException;
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
        $links = [];
        array_push($links, ...$dom->find('a'));

        return $this->getHtml($this->getContentByAvarageLength($links));
    }

    /**
     * @param array $links
     * @return string
     */
    protected function getContentByDBSCAN(array $links): string
    {
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

        $html = '';
        foreach ($res as $i => $re) {
            $html .= "<h2>$i</h2>";
            $html .= implode('<hr>', array_keys($res[$i]));
        }

        return $html;
    }

    /**
     * @param array $links
     * @return string
     */
    protected function getContentByAvarageLength(array $links): string
    {
        //$links = $this->removeEmptyLinks($links);
        //$avg   = $this->getAvarageLinkLength($links);
        //$links = $this->removeBelowAvarage($links, $avg);


        // Bake HTML.
        $html = array_reduce(
            $links,
            function ($html, $link) {
                $h3 = $link->find('h3 div');
                try {
                    return $html . $h3->text() . '<br/>';
                } catch (EmptyCollectionException $ex) {
                    return $html . '';
                }
            },
            ''
        );
        return $html;
    }

    /**
     * @param array $links
     * @param float $avg
     * @return array
     */
    protected function removeBelowAvarage(array $links, float $avg): array
    {
        return array_filter(
            $links,
            function ($link) use ($avg) {
                return strlen($link->text()) > $avg;
            }
        );
    }

    /**
     * @param array $links
     * @return float
     */
    protected function getAvarageLinkLength(array $links): float
    {
        $sum = 0;
        foreach ($links as $i => $link) {
            $sum += strlen($link->text());
        }
        return $sum / count($links);
    }

    /**
     * @param array $links
     * @return array
     */
    protected function removeEmptyLinks(array $links): array
    {
        return array_values(
            array_filter(
                $links,
                function ($elem) {
                    return strlen($elem->text()) > 0;
                }
            )
        );
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
