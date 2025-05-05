<?php

namespace App\Http\Controllers;

use App\Models\Insured;
use App\Services\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Rules\CpfCnpj;
use App\Models\PersonalAccessToken;

class InsuredController extends Controller
{
    /**
*  @OA\GET(
*      path="/api/insureds",
*      summary="Get all insured",
*      description="Get all insured",
*      tags={"Insureds"},
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
*  @OA\POST(
*      path="/api/insureds",
*      summary="Create a insured",
*      description="Create a insured",
*      tags={"Insureds"},
*      security={{"bearerAuth":{}}},
*      @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*           type="object",
*           required={"name","cpf_cnpj","email","celular","cep","endereco","bairro","cidade","uf"},
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="cpf_cnpj", type="string"),
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="celular", type="string"),
*           @OA\Property(property="cep", type="string"),
*           @OA\Property(property="endereco", type="string"),
*           @OA\Property(property="complemento", type="string"),
*           @OA\Property(property="bairro", type="string"),
*           @OA\Property(property="cidade", type="string"),
*           @OA\Property(property="uf", type="string"),
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
            'celular' => 'required'

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
*  @OA\GET(
*      path="/api/insureds/{id}",
*      summary="Get insured by id",
*      description="Get insured by id",
*      tags={"Insureds"},
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
*  @OA\PUT(
*      path="/api/insureds/{id}",
*      summary="Update a insured",
*      description="Update a insured",
*      tags={"Insureds"},
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
*           required={"name","cpf_cnpj","email","celular"},
*           @OA\Property(property="name", type="string"),
*           @OA\Property(property="cpf_cnpj", type="string"),
*           @OA\Property(property="email", type="string"),
*           @OA\Property(property="celular", type="string"),
*           @OA\Property(property="cep", type="string"),
*           @OA\Property(property="endereco", type="string"),
*           @OA\Property(property="complemento", type="string"),
*           @OA\Property(property="bairro", type="string"),
*           @OA\Property(property="cidade", type="string"),
*           @OA\Property(property="uf", type="string"),
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
            'name' => 'required',
            'cpf_cnpj' => ['required', new CpfCnpj],
            'email' => 'required|email',
            'celular' => 'required'

        ]);

        try{

            $insured = Insured::update($request->all());

            return ApiResponse::success($insured);


        }catch(ModelNotFoundException $e){

            return ApiResponse::error('Segurado não encontrado');

        }
    }


    /**
*  @OA\Delete(
*      path="/api/insureds",
*      summary="Delete a insured",
*      description="Delete a insured",
*      tags={"Insureds"},
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
