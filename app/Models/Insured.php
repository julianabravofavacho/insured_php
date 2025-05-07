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
        'cell_phone',
        'postal_code',
        'address',
        'address_line2',
        'neighborhood',
        'city',
        'state'
    ];


}
