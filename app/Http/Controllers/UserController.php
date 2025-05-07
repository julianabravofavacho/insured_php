<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ApiResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    
    
    /**
*  @OA\Get(
*      path="/api/User",
*      summary="Get all users",
*      description="Get all users",
*      tags={"User"},
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
*  @OA\Post(
*      path="/api/User",
*      summary="Create a user",
*      description="Create a user",
*      tags={"User"},
*      security={{"bearerAuth":{}}},
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="password", type="string"),
*           @OA\Property(property="is_adm", type="integer", default=1),
*           @OA\Property(property="is_active", type="integer", default=1),
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

            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'is_active' => 'in:0,1',
                'is_adm' => 'in:0,1'
            ]);

            $user = User::create($request->all());

            return ApiResponse::success($user);

    }

    /**
*  @OA\Get(
*      path="/api/User/{id}",
*      summary="Get user by id",
*      description="Get user by id",
*      tags={"User"},
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
*  @OA\Put(
*      path="/api/User/{id}",
*      summary="Update a user",
*      description="Update a user",
*      tags={"User"},
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
*           required={"name", "id"},
*           @OA\Property(property="id", type="string"),
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="password", type="string"),
*           @OA\Property(property="is_active", type="integer"),
*           @OA\Property(property="is_adm", type="integer"),
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
            'id' => 'required',
            'name' => 'required',
            'email' => 'email|unique:users,email,' .$id,
            'is_active' => 'in:0,1',
            'is_adm' => 'in:0,1'
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
*      path="/api/User/{id}",
*      summary="Delete a user",
*      description="Delete a user",
*      tags={"User"},
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
