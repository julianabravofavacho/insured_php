<?php

namespace App\Http\Controllers;

use App\Services\ApiResponse;
use Illuminate\Http\Request;


class AuthController extends Controller
{
/**
*  @OA\POST(
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
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $email = $request->email;
        $password = $request->password;

        $attempt = auth()->attempt(
            [
                'email' => $email,
                'password' => $password
            ]
        );

        if(!$attempt){
            return ApiResponse::unathourized();
        }

        $user = auth()->user();

        $adm = $user->is_adm;

        $permissions = $adm == 0 ? ["show"] : ["*"];

        $token = $user->createToken($user->name, $permissions)->plainTextToken;

        return ApiResponse::success(
            [
                'token' => $token,
                'expiresAt' => $user->email,
                'abilities' => $permissions
            ]
            );
    }

  /**
*  @OA\DELETE(
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
