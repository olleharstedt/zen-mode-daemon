<?php

namespace zenmodedaemon;

/**
 * Test clustering of links.
 */

require "vendor/autoload.php";

$plot = new \PHPlot(800, 600);
$plot->SetImageBorderType('plain');

$data = [
    [0, 10],
    [1, 20],
    [1, 20]
];

$plot->SetPlotType('bars');
$plot->SetDataType('text-data');
$plot->SetDataValues($data);

// Main plot title:
$plot->SetTitle('Links');

// Make sure Y axis starts at 0:
$plot->SetPlotAreaWorld(null, 0, null, null);

$plot->DrawGraph();
