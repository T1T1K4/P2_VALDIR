<?php

declare(strict_types=1);

namespace ProductsSrpDemo\Domain;

use ProductsSrpDemo\Contracts\ProductValidator;

class SimpleProductValidator implements ProductValidator
{
    private const MIN_NAME_LENGTH = 2;
    private const MAX_NAME_LENGTH = 100;

    public function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['name']) || empty(trim((string) $data['name']))) {
            $errors['name'] = 'Nome é obrigatório';
        } else {
            $name = trim((string) $data['name']);
            $nameLength = strlen($name);
            
            if ($nameLength < self::MIN_NAME_LENGTH) {
                $errors['name'] = 'Nome deve ter pelo menos ' . self::MIN_NAME_LENGTH . ' caracteres';
            } elseif ($nameLength > self::MAX_NAME_LENGTH) {
                $errors['name'] = 'Nome deve ter no máximo ' . self::MAX_NAME_LENGTH . ' caracteres';
            }
        }

        if (!isset($data['price'])) {
            $errors['price'] = 'Preço é obrigatório';
        } else {
            $price = $data['price'];
            
            if (!is_numeric($price)) {
                $errors['price'] = 'Preço deve ser um número válido';
            } else {
                $priceFloat = (float) $price;
                if ($priceFloat < 0) {
                    $errors['price'] = 'Preço deve ser maior ou igual a zero';
                }
            }
        }

        return $errors;
    }
}

