<?php

namespace App\Http\Controllers;

use App\Models\Insured;
use App\Services\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Rules\CpfCnpj;

class InsuredController extends Controller
{
    /**
*  @OA\Get(
*      path="/api/Insured",
*      summary="Get all insured",
*      description="Get all insured",
*      tags={"Insured"},
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
        return ApiResponse::success(Insured::all());
    }

    /**
*  @OA\Post(
*      path="/api/Insured",
*      summary="Create a insured",
*      description="Create a insured",
*      tags={"Insured"},
*      security={{"bearerAuth":{}}},
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"name","cpf_cnpj","email","cell_phone"},
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="cpf_cnpj", type="string"),
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="cell_phone", type="string"),
*           @OA\Property(property="postal_code", type="string"),
*           @OA\Property(property="address", type="string"),
*           @OA\Property(property="address_line2", type="string"),
*           @OA\Property(property="neighborhood", type="string"),
*           @OA\Property(property="city", type="string"),
*           @OA\Property(property="state", type="string"),
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'cpf_cnpj' => ['required', new CpfCnpj],
            'email' => 'required|email',
            'cell_phone' => 'required'

        ]);

        try{

            $data = $request->all();

            $insured = Insured::create($data);

            return ApiResponse::success($insured);

        }catch(ModelNotFoundException $e){

            return ApiResponse::error("Não foi possível incluir o segurado");

        }

        
    }

   /**
*  @OA\Get(
*      path="/api/Insured/{id}",
*      summary="Get insured by id",
*      description="Get insured by id",
*      tags={"Insured"},
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
*          description="insured not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function show(string $id)
    {

        $insured = Insured::find($id);

        if($insured){
            return ApiResponse::success($insured);
        }

        return ApiResponse::error('Segurado não encontrado');
    }

    /**
*  @OA\Put(
*      path="/api/Insured/{id}",
*      summary="Update a insured",
*      description="Update a insured",
*      tags={"Insured"},
*      security={{"bearerAuth":{}}},
*      @OA\Parameter(
*          name="id",
*          in="path",
*          description="ID do período",
*          required=true,
*          @OA\Schema(
*              type="integer"
*          )
*      ),
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"name","cpf_cnpj","email","cell_phone"},
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="cpf_cnpj", type="string"),
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="cell_phone", type="string"),
*           @OA\Property(property="postal_code", type="string"),
*           @OA\Property(property="address", type="string"),
*           @OA\Property(property="address_line2", type="string"),
*           @OA\Property(property="neighborhood", type="string"),
*           @OA\Property(property="city", type="string"),
*           @OA\Property(property="state", type="string"),
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
*          description="insured not found",
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
            'name'         => 'required',
            'cpf_cnpj'     => ['required', new CpfCnpj],
            'email'        => 'required|email',
            'cell_phone'   => 'required',
            'postal_code'  => 'nullable',
            'address'      => 'nullable',
            'address_line2'=> 'nullable',
            'neighborhood' => 'nullable',
            'city'         => 'nullable',
            'state'        => 'nullable',
        ]);

        try{

            $insured = Insured::findOrFail($id);
            $insured->update($request->all());
            $insured->refresh();

            return ApiResponse::success($insured);


        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Segurado não encontrado');

        }
    }


    /**
*  @OA\Delete(
*      path="/api/Insured/{id}",
*      summary="Delete a insured",
*      description="Delete a insured",
*      tags={"Insured"},
*      security={{"bearerAuth":{}}},
*      @OA\Parameter(
*          name="id",
*          in="path",
*          description="ID do segurado",
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
*          description="insured not found",
*          @OA\MediaType(
*              mediaType="application/json",
*          )
*      ),
*
*  )
*/
    public function destroy(string $id){

        $insured = Insured::find($id);

        if($insured){
            $insured->delete();

            return ApiResponse::success($insured);

        }

        return ApiResponse::error('Segurado não encontrado');

    }
}
