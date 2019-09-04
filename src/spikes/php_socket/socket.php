<?php

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

//unlink('/tmp/test.html');

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

    $msg = "HTTP/1.1 200\r\nContent-Type: text/html\r\n\r\n"
        . "<!DOCTYPE html><html><head></head><body><h1>asd</h1></body></html>\r\n\r\n";
    socket_write($msgsock, $msg, strlen($msg));
    socket_close($msgsock);
    continue;

    do {
        if (false === ($buf = socket_read($msgsock, 2048, PHP_NORMAL_READ))) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsock)) . "\n";
            break 2;
        }
        if (!$buf = trim($buf)) {
            continue;
        }
        if ($buf == 'quit') {
            break;
        }
        if ($buf == 'shutdown') {
            socket_close($msgsock);
            break 2;
        }
        echo $buf . PHP_EOL;
        $talkback = "<!DOCTYPE html><html></html>";
        socket_write($msgsock, $talkback, strlen($talkback));
        echo "$buf\n";
    } while (true);
    socket_close($msgsock);
} while (true);
