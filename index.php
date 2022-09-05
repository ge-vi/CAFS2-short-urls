<?php

session_start();

$_SESSION['url_ts'] = $_SESSION['url_ts'] ?? [];

$_SESSION['limit'] = 10;
$_SESSION['time_span'] = 60;

require_once __DIR__ . '/resolver.php';
$links = getAllLinks();

if (isset($_SESSION['url_ts'][0])) {
    $timeToWait = $_SESSION['time_span'] - (time() - $_SESSION['url_ts'][0]);
}

$err = $_GET['err'] ?? false;

?>

<!doctype html>
<html lang="lt">

<head>
    <meta charset="utf-8">
    <title>Nuorodų nukreipimo sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php if (isset($err) && $err == 1): ?>
    <p class="error">Duomenų apdorojimo klaida. Prašome pateikti užklausą vėliau.</p>
<?php elseif (isset($err) && $err == 2): ?>
    <p class="error">Prašome pateikti tinkamą interneto nuorodą.</p>
<?php elseif (isset($err) && $err == 3): ?>
    <p class="error">Trumpoji nuoroda nerasta. Sukurkite naują.</p>
<?php elseif (isset($err) && $err == 4): ?>
    <p class="error">Išnaudojote limitą: <?= $_SESSION['limit'] ?>. Palaukite: <?= $timeToWait ?> sek.</p>
<?php endif; ?>


<h1>Nuorodų nukreipimo sistema</h1>

<h2>Kurti naują nuorodą</h2>

<form action="/redirect.php" method="post">
    <label for="link">Ilga nuoroda</label>
    <input type="url" name="link" id="link" placeholder="https://...">
    <button>Generuoti trumpąją nuorodą</button>
</form>

<?php if (isset($_GET['code'])): ?>
    <h2>Jūsų nuoruoda</h2>
    <a target="_blank"
       href="/redirect.php?code=<?= $_GET['code'] ?>"><?= $_SERVER['HTTP_HOST'] . '/redirect.php?code=' . $_GET['code']; ?></a>

    <br>

    <p>Per <?= $_SESSION['time_span'] ?> sekindžių leidžiama sugeneruoti <?= $_SESSION['limit'] ?> trumpųjų
        nuorodų.</p>
    <p>Jums liko:<kbd> <?= $_SESSION['limit'] - count($_SESSION['url_ts']) ?></kbd></p>

<?php endif; ?>


<?php if (!empty($links)): ?>

    <h2>Esamos nuorodos</h2>

    <ol class="no-bullets">
        <?php foreach ($links as $key => $link): ?>
            <li>
                <a href="/redirect.php?code=<?= $key ?>"><?= $key ?></a>
                <br>
                <small><?= $link ?></small>
            </li>
        <?php endforeach; ?>
    </ol>

<?php endif; ?>

<footer>
    <p>2022<br>CAFS2<br><a href="https://github.com/ge-vi/CAFS2-short-urls">github.com/ge-vi/CAFS2-short-urls</a></p>
    <p><a href="/">home page</a></p>
</footer>

</body>
</html>
