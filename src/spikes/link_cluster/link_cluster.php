<?php

namespace zenmodedaemon;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\AbstractNode;
use PHPHtmlParser\Dom\TextNode;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Clustering\KMeans;
use Phpml\Clustering\DBSCAN;

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

/**
 * Test clustering of links.
 */

require "vendor/autoload.php";

$bing   = file_get_contents(__DIR__ . '/data/something_nice_bing.html');
$google = file_get_contents(__DIR__ . '/data/something_nice_google.html');

$dom = new Dom();
$dom->load($bing);

$links = [];
array_push($links, ...$dom->find('a'));

if (empty($links)) {
    echo 'No links' . PHP_EOL;
    return 1;
}

$plotData = [];

usort(
    $links,
    function (AbstractNode $a, AbstractNode $b) {
        return strlen($a->text()) - strlen($b->text());
    }
);

$links = array_values(
    array_filter(
        $links,
        function ($elem) {
            return strlen($elem->text()) > 0;
        }
    )
);

$sum = 0;
foreach ($links as $i => $link) {
    //echo $link->text() . PHP_EOL;
    $sum += strlen($link->text());
    $plotData[] = [
        $i,
        strlen($link->text()),
        countAllChildren($link)
    ];
}
$avg = $sum / count($links);
$mean = strlen($links[round(count($links) / 2)]->text());
//echo 'Sum: ' . $sum . PHP_EOL;
//echo 'Avg: ' . $avg . PHP_EOL;
//echo 'Mean: ' . $mean . PHP_EOL;

foreach ($links as $link) {
    if (strlen($link->text()) > $avg) {
        //echo $link->text() . PHP_EOL;
    }
}
//return 0;

$plot = new \PHPlot(1024, 800);
$plot->SetImageBorderType('plain');

$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($plotData);

// Main plot title:
$plot->SetTitle('Links');

// Make sure Y axis starts at 0:
$plot->SetPlotAreaWorld(null, 0, null, null);

$plot->DrawGraph();

/**
 * @return int
 */
function countAllChildren(AbstractNode $node): int
{
    $res = 0;
    foreach ($node->getChildren() as $child) {
        if ($child instanceof TextNode) {
            $res += 1;
        } else {
            $res += 1 + countAllChildren($child);
        }
    }
    return $res;
}
