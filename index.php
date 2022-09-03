<?php

session_start();
require_once __DIR__ . '/resolver.php';
$links = getAllLinks();

?>

<!doctype html>
<html lang="lt">

<head>
    <meta charset="utf-8">
    <title>Nuorodų trumpintuvas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<ul>
    <?php foreach ($links as $key => $link): ?>

        <li><a href="/redirect.php?code=<?= $key ?>"><?= $key ?></a> : <?= $link ?></li>

    <?php endforeach; ?>
</ul>

</body>
</html>
