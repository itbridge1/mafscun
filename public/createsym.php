<?php

$target = '/home/sites/38a/a/a6e33aefc0/httpdocs/';
$link = '/home/sites/38a/a/a6e33aefc0/bagmati/public';
$a = symlink($target, $link);
if ($a) {
    echo "Done";
} else {
    echo "Failed to create symlink: " . error_get_last()['message'];
}
