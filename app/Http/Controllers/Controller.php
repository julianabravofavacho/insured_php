<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckUserPermission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="Insurance", version="0.1")
 *
 * @OA\SecurityScheme(
 * type="http", 
 * description="Access token autentication",
 * name="Authorization",
 * in="header",
 * scheme="bearer",
 * bearerFormat="JWT",
 * securityScheme="bearerAuth")
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;  
    
    public function __construct()
    {
        $this->middleware(CheckUserPermission::class)->only(['store', 'update', 'destroy']);
    }
}