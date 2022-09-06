<?php

session_start();

require_once __DIR__ . '/resolver.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $code = $_GET['code'] ?? false;

    if ($code) {
        $link = retrieveLink($code) ?? false;

        if ($link) {
            // success
            header('Location: ' . $link, true, 301);
            exit();
        }
    }

    header('Location: /index.php?err=3', true, 302);
    exit();

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // user input
    $rawLink = (isset($_POST['link']) && !empty($_POST['link'])) ? trim($_POST['link']) : false;

    // data validation
    if (!$rawLink) {
        // no data
        header('Location: /index.php?err=2');
        exit();
    } elseif (filter_var($rawLink, FILTER_VALIDATE_URL) === false) {
        // not valid url
        header('Location: /index.php?err=2');
        exit();
    } elseif (sessionPostsLimiter($_SESSION['url_ts'], $_SESSION['limit'], $_SESSION['time_span'])) {
        // out of credits
        header('Location: /index.php?err=4');
        exit();
    }

    // hooray!!! go with provided data
    $link = htmlentities($rawLink);

    try {
        $code = saveLink($link);
        header('Location: /index.php?code=' . $code, true, 301);
        exit();
    } catch (Exception $e) {
        error_log($e->getMessage());
        header('Location: /index.php?err=1', true, 301);
        exit();
    }
}
