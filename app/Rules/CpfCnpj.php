<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CpfCnpj implements Rule
{
    public function passes($attribute, $value)
    {
        // Remove caracteres não numéricos
        $value = preg_replace('/\D/', '', $value);

        // Verifica se é CPF ou CNPJ pelo tamanho
        if (strlen($value) === 11) {
            return $this->validateCpf($value);
        } elseif (strlen($value) === 14) {
            return $this->validateCnpj($value);
        }

        return false;
    }

    public function message()
    {
        return 'O campo deve ser um CPF ou CNPJ válido.';
    }

    private function validateCpf($cpf)
    {
        // Aqui você pode colocar a lógica de validação de CPF
        // Para simplificar, vou usar uma validação básica
        if (preg_match('/^(\d{11})$/', $cpf)) {
            // Adicione validações específicas de CPF aqui, se desejar
            return true;
        }
        return false;
    }

    private function validateCnpj($cnpj)
    {
        // Aqui você pode colocar a lógica de validação de CNPJ
        // Para simplificar, vou usar uma validação básica
        if (preg_match('/^(\d{14})$/', $cnpj)) {
            // Adicione validações específicas de CNPJ aqui, se desejar
            return true;
        }
        return false;
    }
}