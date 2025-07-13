<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        '/api/*',
        '*/api/*',
        'login',
        'register',
        '/login',
        '/register',
    ];

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        // Always exclude API routes
        if (str_contains($request->path(), 'api/')) {
            return true;
        }

        return parent::inExceptArray($request);
    }
}
