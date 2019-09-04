<?php

$notif = inotify_init();

$result = inotify_add_watch($notif, '/tmp/java7', IN_ALL_EVENTS);

echo $result . PHP_EOL;

while (true) {
    $events = inotify_read($notif);
    foreach ($events as $event => $details) {
        echo $details['mask'] . PHP_EOL;
        switch (true) {
            case ($details['mask'] & IN_ACCESS):
                echo 'IN_ACCESS' . PHP_EOL;
                break;
            case ($details['mask'] & IN_ATTRIB):
                echo 'IN_ATTRIB' . PHP_EOL;
                break;
            case ($details['mask'] & IN_CLOSE_WRITE):
                echo 'IN_CLOSE_WRITE' . PHP_EOL;
                break;
            case ($details['mask'] & IN_CLOSE_NOWRITE):
                echo 'IN_CLOSE_NOWRITE' . PHP_EOL;
                break;
            case ($details['mask'] & IN_CREATE):
                echo 'IN_CREATE' . PHP_EOL;
                break;
            case ($details['mask'] & IN_DELETE):
                echo 'IN_DELETE' . PHP_EOL;
                break;
            case ($details['mask'] & IN_DELETE_SELF):
                echo 'IN_DELETE_SELF' . PHP_EOL;
                break;
            case ($details['mask'] & IN_MODIFY):
                echo 'IN_MODIFY' . PHP_EOL;
                break;
            case ($details['mask'] & IN_MOVE_SELF):
                echo 'IN_MOVE_SELF' . PHP_EOL;
                break;
            case ($details['mask'] & IN_MOVED_FROM):
                echo 'IN_MOVED_FROM' . PHP_EOL;
                break;
            case ($details['mask'] & IN_MOVED_TO):
                echo 'IN_MOVED_TO' . PHP_EOL;
                break;
            case ($details['mask'] & IN_OPEN):
                //$filename = $details['name'];
                $content = rand(1, 1000);
                echo 'IN_OPEN ' . $content . PHP_EOL;
                //file_put_contents('/tmp/java7/' . $filename, $content);
                //break 3;
                break;
            case ($details['mask'] & IN_MOVE):
                echo 'IN_MOVE' . PHP_EOL;
                break;
            case ($details['mask'] & IN_CLOSE):
                echo 'IN_CLOSE' . PHP_EOL;
                break;
            case ($details['mask'] & IN_IGNORED):
                echo 'IN_IGNORED' . PHP_EOL;
                break;
            case ($details['mask'] & IN_ISDIR):
                echo 'IN_ISDIR' . PHP_EOL;
                break;
            case ($details['mask'] & IN_Q_OVERFLOW):
                echo 'IN_Q_OVERFLOW' . PHP_EOL;
                break;
            case ($details['mask'] & IN_UNMOUNT):
                echo 'IN_UNMOUNT' . PHP_EOL;
                break;
            default:
                echo 'Unknown mask' . PHP_EOL;
                break;
        }
    }
}
