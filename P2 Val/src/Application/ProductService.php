<?php

declare(strict_types=1);

namespace ProductsSrpDemo\Application;

use ProductsSrpDemo\Contracts\ProductRepository;
use ProductsSrpDemo\Contracts\ProductValidator;
use ProductsSrpDemo\Domain\Product;

class ProductService
{
    private ProductRepository $repository;
    private ProductValidator $validator;

    public function __construct(ProductRepository $repository, ProductValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function create(array $data): array
    {
        $errors = $this->validator->validate($data);
        
        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        $id = $this->repository->getNextId();
        $name = trim((string) $data['name']);
        $price = (float) $data['price'];

        $product = new Product($id, $name, $price);

        $saved = $this->repository->save($product);
        
        if (!$saved) {
            return [
                'success' => false,
                'errors' => ['general' => 'Erro ao salvar o produto']
            ];
        }

        return [
            'success' => true,
            'product' => $product
        ];
    }

    public function list(): array
    {
        return $this->repository->findAll();
    }
}

