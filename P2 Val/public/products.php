<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProductsSrpDemo\Application\ProductService;
use ProductsSrpDemo\Domain\SimpleProductValidator;
use ProductsSrpDemo\Infra\FileProductRepository;

try {
    // Inicializar dependências
    $repository = new FileProductRepository(__DIR__ . '/../storage/products.txt');
    $validator = new SimpleProductValidator();
    $productService = new ProductService($repository, $validator);

    // Buscar todos os produtos
    $products = $productService->list();

} catch (Exception $e) {
    $products = [];
    $error = 'Erro ao carregar produtos: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .links {
            text-align: center;
            margin-bottom: 20px;
        }
        .links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 10px;
            padding: 8px 16px;
            border: 1px solid #007bff;
            border-radius: 4px;
            display: inline-block;
        }
        .links a:hover {
            background-color: #007bff;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .price {
            text-align: right;
            font-weight: bold;
        }
        .id {
            text-align: center;
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Produtos</h1>
        
        <div class="links">
            <a href="index.php">Cadastrar Novo Produto</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($products)): ?>
            <div class="empty-message">
                Nenhum produto cadastrado ainda.<br>
                <a href="index.php" style="color: #007bff; text-decoration: none;">
                    Clique aqui para cadastrar o primeiro produto
                </a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th class="price">Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="id"><?php echo htmlspecialchars((string) $product->getId()); ?></td>
                            <td><?php echo htmlspecialchars($product->getName()); ?></td>
                            <td class="price">R$ <?php echo number_format($product->getPrice(), 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px; text-align: center; color: #666;">
                Total de produtos: <?php echo count($products); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

