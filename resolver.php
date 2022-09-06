<?php

/**
 * Saves given link to file and returns key
 *
 * @param string $link
 * @return string|null
 * @throws Exception
 */
function saveLink(string $link): ?string
{
    $links = json_decode(
        file_get_contents('links-map.json'),
        true
    );

    do {
        $code = substr(md5(time()), 0, random_int(5, 10));
    } while (array_key_exists($code, $links));

    $links[$code] = $link;

    if (file_put_contents('links-map.json', json_encode($links))) {
        sessionPostsIncrement();
        return $code;
    } else {
        return null;
    }
}

/**
 * Retrieves link by code
 *
 * @param string $code
 * @return string|null
 */
function retrieveLink(string $code): ?string
{
    $links = json_decode(
        file_get_contents('links-map.json'),
        true
    );

    if (array_key_exists($code, $links)) {
        return $links[$code];
    }

    return null;
}

function getAllLinks(int $limit = 10): array
{
    $links = json_decode(
        file_get_contents('links-map.json'),
        true
    );

    return array_reverse(array_slice($links, -$limit));
}

/**
 * Determines limit reach.
 * If limit is reached returns `true`.
 * If limit isn't reached returns `false` so service can continue working.
 *
 * @param array $urlTs
 * @param int $limit
 * @param int $timeSpan
 * @return bool
 */
function sessionPostsLimiter(array &$urlTs, int $limit, int $timeSpan): bool
{
    if (count($urlTs) < $limit) {
        return false;
    } else {
        $urlTs = array_slice($urlTs, -(--$limit));
        $diff = time() - $urlTs[0];
        // 45 <= 60 -> true: limit
        // 100 <= 60 -> false: don't limit
        return $diff <= $timeSpan;
    }
}

function sessionPostsIncrement(): void
{
    $_SESSION['url_ts'][] = time();
}

function sessionErrorSet(int|string $err): void
{
    $_SESSION['err'] = $err;
}

function sessionErrorGet(): int|string
{
    $errNum = $_SESSION['err'] ?? 0;
    unset($_SESSION['err']);
    return $errNum;
}
