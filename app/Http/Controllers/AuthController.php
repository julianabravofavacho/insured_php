<?php

namespace App\Http\Controllers;

use App\Services\ApiResponse;
use Illuminate\Http\Request;


class AuthController extends Controller
{
/**
*  @OA\Post(
*      path="/api/Authentication/login",
*      summary="Login",
*      description="Login",
*      tags={"Authentication"},
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"name","email","password"},
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="password", type="string"),
*         )
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function login (Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users',
            'password' => 'required',
        ]);
    
        if (! auth()->attempt($request->only('email','password'))) {
            return ApiResponse::unathourized();
        }
    
        $user = auth()->user();
        $abilities = $user->is_adm
            ? ['*']
            : ['show'];
    
        $newToken = $user->createToken($user->name, $abilities);

        $pat = $newToken->accessToken;
        $minutes = (int) config('sanctum.expiration');
        $pat->expires_at = now()->addMinutes($minutes);
        $pat->save();
    
        return ApiResponse::success([
            'token'     => $newToken->plainTextToken,
            'expiresAt' => $newToken->accessToken->expires_at?->toDateTimeString(),
            'abilities' => $abilities,
        ]);
    }

  /**
*  @OA\Delete(
*      path="/api/Authentication/logout",
*      summary="Revoke the last user token",
*      description="Revoke the last user token",
*      tags={"Authentication"},
*      security={{"bearerAuth":{}}},
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=401,
*          description="Unauthenticated",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success('Deslogado com sucesso');
    }

    
}
