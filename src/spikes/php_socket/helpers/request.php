<?php

namespace zenmodedaemon\helpers\request;

/**
 * Bake buffert and return array with GET arguments.
 *
 * @param string $buffert
 * @return array<string, string>
 * @throws \Exception
 */
function getParams(string $buffert): array
{
    $flattenGet = [];

    $parts = explode(' ', $buffert);
    if (count($parts) === 3 && substr($parts[1], 0, 2) === '/?') {
        $stripped = substr($parts[1], 2);
        $get = explode('=', $stripped);
        if (count($get) % 2 !== 0) {
            echo 'get has not even number of args' . PHP_EOL;
            throw new \Exception('GET has not even number of args');
        }
        for ($i = 0; $i < count($get); $i += 2) {
            $flattenGet[$get[$i]] = $get[$i + 1];
        }
    }

    return $flattenGet;
}

/**
 * @param string $content
 * @return string
 */
function bakeHeader(string $content): string
{
    $length = strlen($content);
    return "HTTP/1.1 200\r\nContent-Type: text/html\r\nContent-Length:$length\r\n\r\n";
}
