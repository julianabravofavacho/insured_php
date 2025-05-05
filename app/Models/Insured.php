<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insured extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'cpf_cnpj',
        'email',
        'celular',
        'cep',
        'endereco',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'active'
    ];


}
