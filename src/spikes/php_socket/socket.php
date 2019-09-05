<?php

require "vendor/autoload.php";

use PHPHtmlParser\Dom;

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if (socket_bind($sock, '127.0.0.1', 777) === false) {
    die('cannot bind' . PHP_EOL);
}

if (socket_listen($sock, 5) === false) {
    die('cannot listen' . PHP_EOL);
}

do {
    if (($msgsock = socket_accept($sock)) === false) {
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    }

    $flattenGet = [];

    // Request does NOT work when read is uncommented.
    $buf = '';
    while (true) {
        $tmp = socket_read($msgsock, 4012, PHP_NORMAL_READ);
        if (strlen(trim($tmp)) > 0) {
            $buf .= $tmp;
        } else {
            break;
        }
    }
    if (empty(trim($buf))) {
        socket_close($msgsock);
        continue;
    }
    if ($buf === 'GET /favicon.ico HTTP/1.1') {
        // ignore this request
        socket_close($msgsock);
        continue;
    } else {
        $parts = explode(' ', $buf);
        if (count($parts) === 3 && substr($parts[1], 0, 2) === '/?') {
            $stripped = substr($parts[1], 2);
            $get = explode('=', $stripped);
            if (count($get) % 2 !== 0) {
                echo 'get has not even number of args' . PHP_EOL;
                socket_close($msgsock);
                continue;
            }
            for ($i = 0; $i < count($get); $i += 2) {
                $flattenGet[$get[$i]] = $get[$i + 1];
            }
        }
    }
    echo $buf . PHP_EOL;

    if (isset($flattenGet['__site'])) {
        $configFile = __DIR__ . '/sites/' . $flattenGet['__site'] . '.json';
        if (file_exists($configFile)) {
            $json = json_decode(file_get_contents($configFile));
            $type = $json->type;
        } else {
            $type = 'standard';
        }
        $url = 'https://' . $flattenGet['__site'];
        $site = file_get_contents($url);
        if ($type === 'search_engine') {
            if (isset($json->form_name)) {
                $formName = $json->form_name;
                $dom = new Dom();
                $dom->loadFromUrl($url);
                $form = $dom->find("form[name=$formName]");
            } elseif (isset($json->form_id)) {
                $formId = $json->form_id;
                $dom = new Dom();
                $dom->loadFromUrl($url);
                $form = $dom->find("#$formId");
            }
            $content = "<!DOCTYPE html><html><head></head><body>";
            $content .= "<h1>{$json->name}</h1>";
            $content .= (string) $form;
            $content .= "</body></html>";
        } else {
            $content = $site;
        }
        $length = strlen($content);
        $header = "HTTP/1.1 200\r\nContent-Type: text/html\r\nContent-Length:$length\r\n\r\n";
        $msg = $header . $content;
    } else {
        $content = "<!DOCTYPE html><html><head></head><body><h1>No __site defined in URL</h1></body></html>";
        $length = strlen($content);
        $header = "HTTP/1.1 200\r\nContent-Type: text/html\r\nContent-Length:$length\r\n\r\n";
        $msg = $header . $content;
    }

    socket_write($msgsock, $msg, strlen($msg));
    socket_close($msgsock);
    continue;
} while (true);
