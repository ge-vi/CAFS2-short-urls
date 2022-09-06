<?php


use PHPUnit\Framework\TestCase;

require_once '../resolver.php';

final class TestResolver extends TestCase
{
    public function testLessThanTenPosts(): void
    {
        // setup
        $_SESSION = [
            'limit' => 10,
            'time_span' => 60
        ];
        $_SESSION['url_ts'][] = time();
        $_SESSION['url_ts'][] = time();
        $_SESSION['url_ts'][] = time();

        // exec
        $result = sessionPostsLimiter(
            $_SESSION['url_ts'],
            $_SESSION['limit'],
            $_SESSION['time_span']
        );

        // check
        $this->assertFalse($result);
    }

    public function testMoreThanTenPosts(): void
    {
        // setup
        $_SESSION = [
            'limit' => 10,
            'time_span' => 60
        ];
        for ($i = 0; $i <= 15; $i++) {
            $_SESSION['url_ts'][] = time();
        }

        // exec
        $result = sessionPostsLimiter(
            $_SESSION['url_ts'],
            $_SESSION['limit'],
            $_SESSION['time_span']
        );

        // check
        $this->assertTrue($result);
    }

    public function testMoreThanTenPostsOverTimeLimit(): void
    {
        // setup
        $_SESSION = [
            'limit' => 10,
            'time_span' => 60
        ];
        for ($i = 100; $i >= 0; $i -= 10) {
            $_SESSION['url_ts'][] = time() - $i;
        }

        // exec
        $result = sessionPostsLimiter(
            $_SESSION['url_ts'],
            $_SESSION['limit'],
            $_SESSION['time_span']
        );

        // check
        $this->assertFalse($result);
    }
}
