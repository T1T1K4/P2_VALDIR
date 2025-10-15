<?php

declare(strict_types=1);

namespace ProductsSrpDemo\Contracts;

interface ProductValidator
{
    public function validate(array $data): array;
}

