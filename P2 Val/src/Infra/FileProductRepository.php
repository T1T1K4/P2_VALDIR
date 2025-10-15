<?php

declare(strict_types=1);

namespace ProductsSrpDemo\Infra;

use ProductsSrpDemo\Contracts\ProductRepository;
use ProductsSrpDemo\Domain\Product;

class FileProductRepository implements ProductRepository
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->ensureFileExists();
    }

    public function save(Product $product): bool
    {
        $data = json_encode($product->toArray(), JSON_UNESCAPED_UNICODE) . "\n";
        
        $result = file_put_contents($this->filePath, $data, FILE_APPEND | LOCK_EX);
        
        return $result !== false;
    }

    public function findAll(): array
    {
        if (!file_exists($this->filePath)) {
            return [];
        }

        $content = file_get_contents($this->filePath);
        if ($content === false || empty(trim($content))) {
            return [];
        }

        $products = [];
        $lines = explode("\n", trim($content));
        
        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }
            
            $data = json_decode($line, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                $products[] = Product::fromArray($data);
            }
        }

        return $products;
    }

    public function getNextId(): int
    {
        $products = $this->findAll();
        
        if (empty($products)) {
            return 1;
        }

        $maxId = 0;
        foreach ($products as $product) {
            if ($product->getId() > $maxId) {
                $maxId = $product->getId();
            }
        }

        return $maxId + 1;
    }

    private function ensureFileExists(): void
    {
        $directory = dirname($this->filePath);
        
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        if (!file_exists($this->filePath)) {
            touch($this->filePath);
        }
    }
}

