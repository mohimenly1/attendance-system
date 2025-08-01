<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || $request->user()->role->value !== $role) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        return $next($request);
    }
}