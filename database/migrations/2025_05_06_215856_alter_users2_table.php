<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('insureds', function (Blueprint $table) {
            // 1) remove a coluna antiga
            $table->dropColumn('active');

            // 2) renomeia cada coluna
            $table->renameColumn('celular',    'cell_phone');
            $table->renameColumn('cep',        'postal_code');
            $table->renameColumn('endereco',   'address');
            $table->renameColumn('complemento','address_line2');
            $table->renameColumn('bairro',     'neighborhood');
            $table->renameColumn('cidade',     'city');
            $table->renameColumn('uf',         'state');
        });
    }

    public function down(): void
    {
        Schema::table('insureds', function (Blueprint $table) {
            // 1) reverte os renames
            $table->renameColumn('cell_phone',     'celular');
            $table->renameColumn('postal_code',    'cep');
            $table->renameColumn('address',        'endereco');
            $table->renameColumn('address_line2',  'complemento');
            $table->renameColumn('neighborhood',   'bairro');
            $table->renameColumn('city',           'cidade');
            $table->renameColumn('state',          'uf');

            // 2) recria a coluna active
            $table->tinyInteger('active')->default(1);
        });
    }
};
