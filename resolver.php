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
        $code = _generateCode();
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
 * @throws Exception
 */
function _generateCode(): string
{
    return substr(md5(time()), 0, random_int(5, 10));
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

function sessionPostsLimiter(): bool
{
    // todo implement
    if (count($_SESSION['url_ts']) <= $_SESSION['limit']) {
        return false;
    } else {
        return $_SESSION['url_ts'][0] <=> time();
    }
}

function sessionPostsIncrement(): void
{
    $_SESSION['url_ts'][] = time();
}
