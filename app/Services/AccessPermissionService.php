<?php

namespace App\Services;

use Laravel\Sanctum\PersonalAccessToken;

class AccessPermissionService
{
    /**
     * Verifica se o usuário autenticado possui a permissão '*'
     * no último token de acesso criado.
     *
     * @param int $userId
     * @return bool
     */
    public function userHasWildcardPermission(int $userId): bool
    {
        $token = PersonalAccessToken::where('tokenable_id', $userId)
            ->orderByDesc('created_at')
            ->first();

        return $token && in_array('*', $token->abilities);
    }

    /**
     * Verifica se o usuário possui uma permissão específica.
     *
     * @param int $userId
     * @param string $permission
     * @return bool
     */
    public function userHasPermission(int $userId, string $permission): bool
    {
        $token = PersonalAccessToken::where('tokenable_id', $userId)
            ->orderByDesc('created_at')
            ->first();

        return $token && in_array($permission, $token->abilities);
    }
}
