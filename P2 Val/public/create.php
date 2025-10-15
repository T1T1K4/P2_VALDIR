<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProductsSrpDemo\Application\ProductService;
use ProductsSrpDemo\Domain\SimpleProductValidator;
use ProductsSrpDemo\Infra\FileProductRepository;

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Location: index.php');
    exit;
}

try {
    // Inicializar dependências
    $repository = new FileProductRepository(__DIR__ . '/../storage/products.txt');
    $validator = new SimpleProductValidator();
    $productService = new ProductService($repository, $validator);

    // Obter dados do POST
    $data = [
        'name' => $_POST['name'] ?? '',
        'price' => $_POST['price'] ?? 0
    ];

    // Tentar criar o produto
    $result = $productService->create($data);

    if ($result['success']) {
        // Sucesso - redirecionar com mensagem de sucesso
        header('Location: index.php?success=true');
        exit;
    } else {
        // Erro de validação - redirecionar com erros
        $params = [];
        
        if (isset($result['errors']['name'])) {
            $params['name_error'] = $result['errors']['name'];
        }
        
        if (isset($result['errors']['price'])) {
            $params['price_error'] = $result['errors']['price'];
        }
        
        if (isset($result['errors']['general'])) {
            $params['error'] = $result['errors']['general'];
        }
        
        // Preservar valores inseridos
        $params['name'] = htmlspecialchars($data['name']);
        $params['price'] = htmlspecialchars($data['price']);
        
        $queryString = http_build_query($params);
        header('Location: index.php?' . $queryString);
        exit;
    }

} catch (Exception $e) {
    // Erro interno
    http_response_code(500);
    $params = [
        'error' => 'Erro interno do servidor. Tente novamente.',
        'name' => htmlspecialchars($data['name'] ?? ''),
        'price' => htmlspecialchars($data['price'] ?? '')
    ];
    $queryString = http_build_query($params);
    header('Location: index.php?' . $queryString);
    exit;
}

