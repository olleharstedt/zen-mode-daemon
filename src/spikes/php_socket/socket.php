<?php

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if (socket_bind($sock, '127.0.0.10', 888) === false) {
    die('cannot bind' . PHP_EOL);
}

if (socket_listen($sock, 5) === false) {
    die('cannot listen' . PHP_EOL);
}

do {
    if (($msgsock = socket_accept($sock)) === false) {
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
    }

    $content = "<!DOCTYPE html><html><head></head><body><h1>asd</h1></body></html>";
    $length = strlen($content);
    $header = "HTTP/1.1 200\r\nContent-Type: text/html\r\nContent-Length:$length\r\n\r\n";
    $msg = $header . $content;

    // Request does NOT work when read is uncommented.
    while (true) {
        $buf = socket_read($msgsock, 4012, PHP_NORMAL_READ);
        if (empty(trim($buf))) {
            break;
        }
        echo $buf . PHP_EOL;
    }
    socket_write($msgsock, $msg, strlen($msg));
    socket_close($msgsock);
    continue;
} while (true);
