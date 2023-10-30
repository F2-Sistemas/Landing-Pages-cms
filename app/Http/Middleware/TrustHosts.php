<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        $hosts = array_filter([
            parse_url(config('app.url'), PHP_URL_HOST),
            parse_url($_SERVER['HTTP_HOST'] ?? '', PHP_URL_HOST),
            parse_url($_SERVER['APP_URL'] ?? '', PHP_URL_HOST),
            parse_url($_SERVER['SERVER_NAME'] ?? '', PHP_URL_HOST),
            '0.0.0.0',
        ]);

        return [
            $this->allSubdomainsOfApplicationUrl(),
            ...$hosts,
        ];
    }
}
