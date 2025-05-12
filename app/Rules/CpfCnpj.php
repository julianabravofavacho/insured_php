<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfCnpj implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove tudo que não for dígito
        $digits = preg_replace('/\D/', '', (string) $value);

        // Decide se é CPF ou CNPJ
        if (strlen($digits) === 11) {
            $valid = $this->validateCpf($digits);
        } elseif (strlen($digits) === 14) {
            $valid = $this->validateCnpj($digits);
        } else {
            $valid = false;
        }

        if (! $valid) {
            $fail('O campo deve ser um CPF ou CNPJ válido.');
        }
    }

    private function validateCpf(string $cpf): bool
    {
        // lógica de validação de CPF (ex: regex + dígitos verificadores)
        return (bool) preg_match('/^\d{11}$/', $cpf);
    }

    private function validateCnpj(string $cnpj): bool
    {
        // lógica de validação de CNPJ (ex: regex + dígitos verificadores)
        return (bool) preg_match('/^\d{14}$/', $cnpj);
    }
}
