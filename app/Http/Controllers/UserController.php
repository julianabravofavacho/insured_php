<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckUserPermission;

class UserController extends Controller
{

    
    
    /**
*  @OA\GET(
*      path="/api/users",
*      summary="Get all users",
*      description="Get all users",
*      tags={"Users"},
*      security={{"bearerAuth":{}}},
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
    public function index()
    {
        return ApiResponse::success(User::all());
    }

    /**
*  @OA\POST(
*      path="/api/users",
*      summary="Create a user",
*      description="Create a user",
*      tags={"Users"},
*      security={{"bearerAuth":{}}},
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="password", type="string"),
*           @OA\Property(property="adm", type="integer"),
*         )
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=401,
*          description="Unathourized",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*  )
*/
    public function store(Request $request)
    {
       /* $userId = auth()->id();

        $permission_adm = app(AccessPermissionService::class)->userHasWildcardPermission($userId);

        if($permission_adm){*/

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'adm' => 'required|in:0,1'
            ]);

            $user = User::create($request->all());

            return ApiResponse::success($user);

        //}
        
        //return ApiResponse::unathourized();
    }

    /**
*  @OA\GET(
*      path="/api/users/{id}",
*      summary="Get user by id",
*      description="Get user by id",
*      tags={"Users"},
*      security={{"bearerAuth":{}}},
*      @OA\Parameter(
*         name="id",
*         in="path",
*         description="id",
*         required=true,
*         @OA\Schema(
*               type="integer",
*               format="int64"
*         )
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=404,
*          description="User not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function show(string $id)
    {
        $user = User::find($id);

        if($user){
            return ApiResponse::success($user);
        }

        return ApiResponse::error('Usuário não encontrado');
    }

    /**
*  @OA\PUT(
*      path="/api/users/{id}",
*      summary="Update a user",
*      description="Update a user",
*      tags={"Users"},
*      security={{"bearerAuth":{}}},
*      @OA\Parameter(
*          name="id",
*          in="path",
*          description="ID do usuário",
*          required=true,
*          @OA\Schema(
*              type="integer"
*          )
*      ),
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"name"},
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="password", type="string"),
*           @OA\Property(property="adm", type="integer"),
*         )
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=404,
*          description="User not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|unique:users,email,' .$id,
            'active' => 'in:0,1'
        ]);

        $user = User::find($id);

        if($user){

            $user->update($request->all());

            return ApiResponse::success($user);

        }

        return ApiResponse::error('Usuário não encontrado');
    }



        /**
*  @OA\Delete(
*      path="/api/users",
*      summary="Delete a user",
*      description="Delete a user",
*      tags={"Users"},
*      security={{"bearerAuth":{}}},
*      @OA\Parameter(
*          name="id",
*          in="path",
*          description="ID do usuário",
*          required=true,
*          @OA\Schema(
*              type="integer",
*              format="int64"
*          )
*      ),
*      @OA\Response(
*          response=200,
*          description="OK",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*      @OA\Response(
*          response=404,
*          description="user not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function destroy(string $id){

        $user = User::find($id);

        if($user){
            $user->delete();

            return ApiResponse::success($user);

        }

        return ApiResponse::error('Usuário não encontrado');

    }

}
