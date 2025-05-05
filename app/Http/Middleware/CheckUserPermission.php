<?php

namespace App\Http\Middleware;

use App\Services\AccessPermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->id();
        //dd("ID DO USUÁRIO:" .$userId);
        $access = app(AccessPermissionService::class)->userHasWildcardPermission($userId);

        if (!$access) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        return $next($request);
    }
}
