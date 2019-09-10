<?php

namespace zenmodedaemon\helpers\config;

/**
 * @param string $__site
 * @return string
 */
function getConfigFilename(string $__site)
{
    return ROOT_DIR . '/sites/' . $__site . '.json';
}

/**
 * @param string $configFile
 * @return object
 * @throws \Exception
 */
function getConfigJson(string $configFile)
{
    if (file_exists($configFile)) {
        return json_decode(file_get_contents($configFile));
    } else {
        throw new \Exception('Found no file with name ' . $configFile);
    }
}
