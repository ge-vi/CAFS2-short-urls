<?php

/**
 * Saves given link to file and returns key
 *
 * @param string $link
 * @return string
 * @throws Exception
 */
function saveLink(string $link): string
{
    $links = json_decode(
        file_get_contents('links-map.json'),
        true
    );

    $code = substr(md5(time()), 0, random_int(5, 10));
    $links[] = [$code => $link];

    file_put_contents('links-map.json', json_encode($links));
    return $code;
}

/**
 * Retrieves link by code
 *
 * @param string $code
 * @return string
 */
function retrieveLink(string $code): string
{
    $links = json_decode(
        file_get_contents('links-map.json'),
        true
    );

    if (array_key_exists($code, $links)) {
        return $links[$code];
    }

    return '/';
}

function getAllLinks(int $limit = 10): array
{
    $links = json_decode(
        file_get_contents('links-map.json'),
        true
    );

    return array_slice($links, -$limit);
}
