<?php

declare(strict_types=1);

namespace ProductsSrpDemo\Contracts;

use ProductsSrpDemo\Domain\Product;

interface ProductRepository
{
    public function save(Product $product): bool;
    public function findAll(): array;
    public function getNextId(): int;
}

