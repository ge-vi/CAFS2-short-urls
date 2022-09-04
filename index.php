<?php

session_start();
require_once __DIR__ . '/resolver.php';
$links = getAllLinks();

$err = $_GET['err'] ?? false;

?>

<!doctype html>
<html lang="lt">

<head>
    <meta charset="utf-8">
    <title>Nuorodų trumpintuvas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php if (isset($err) && $err == 1): ?>
    <p class="error">Duomenų apdorojimo klaida. Prašome pateikti užklausą vėliau.</p>
<?php elseif (isset($err) && $err == 2): ?>
    <p class="error">Prašome pateikti tinkamą interneto nuorodą.</p>
<?php elseif (isset($err) && $err == 3): ?>
    <p class="error">Trumpoji nuoroda nerasta. Sukurkite naują.</p>
<?php endif; ?>


<h1>Interneto adresų/nuorodų trumpintuvas</h1>

<h2>Kurti naują nuorodą</h2>

<form action="/redirect.php" method="post">
    <label for="link">Ilga nuoroda</label>
    <input type="url" name="link" id="link" placeholder="https://...">
    <button>Generuoti trumpąją nuorodą</button>
</form>

<?php if (isset($_GET['code'])): ?>
    <h2>Jūsų nuoruoda</h2>
    <a target="_blank" href="/redirect.php?code=<?= $_GET['code'] ?>"><?= $_SERVER['HTTP_HOST'] . '/redirect.php?code=' . $_GET['code']; ?></a>
<?php endif; ?>


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

</body>
</html>
