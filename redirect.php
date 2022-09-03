<?php

require_once __DIR__ . '/resolver.php';

$code = $_GET['code'] ?? false;

if ($code) {
    $link = retrieveLink($code);

    header("Location: " . $link, true, 301);
    exit();
}

header("Location: /index.php", true, 301);
exit();
