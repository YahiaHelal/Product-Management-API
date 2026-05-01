<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromHeader
{
    public function handle(Request $request, Closure $next): Response
    {
        $requestedLocale = strtolower((string) $request->header('Accept-Language', 'en'));
        $locale = str_starts_with($requestedLocale, 'ar') ? 'ar' : 'en';

        app()->setLocale($locale);

        return $next($request);
    }
}
