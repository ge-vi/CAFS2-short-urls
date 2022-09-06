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

    sessionErrorSet('Trumpoji nuoroda nerasta. Sukurkite naują.');
    header('Location: /', true, 302);
    exit();

} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // user input
    $rawLink = (isset($_POST['link']) && !empty($_POST['link'])) ? trim($_POST['link']) : false;

    // data validation
    if (!$rawLink) {
        // no data
        sessionErrorSet('Prašome pateikti tinkamą interneto nuorodą.');
        header('Location: /');
        exit();
    } elseif (filter_var($rawLink, FILTER_VALIDATE_URL) === false) {
        // not valid url
        sessionErrorSet('Prašome pateikti tinkamą interneto nuorodą.');
        header('Location: /');
        exit();
    } elseif (sessionPostsLimiter($_SESSION['url_ts'], $_SESSION['limit'], $_SESSION['time_span'])) {
        // out of credits
        sessionErrorSet(4);
        header('Location: /');
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
        sessionErrorSet('Duomenų apdorojimo klaida. Prašome pateikti užklausą vėliau.');
        header('Location: /', true, 301);
        exit();
    }
}
