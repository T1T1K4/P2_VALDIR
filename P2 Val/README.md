# Sistema de Cadastro de Produtos - SRP Demo

Sistema simples de cadastro e listagem de produtos aplicando os princípios de Single Responsibility Principle (SRP), PSR-4 e organização em camadas.

## Estrutura do Projeto

```
products-srp-demo/
├── composer.json
├── vendor/
│   └── autoload.php
├── src/
│   ├── Contracts/
│   │   ├── ProductRepository.php
│   │   └── ProductValidator.php
│   ├── Application/
│   │   └── ProductService.php
│   ├── Domain/
│   │   ├── Product.php
│   │   └── SimpleProductValidator.php
│   └── Infra/
│       └── FileProductRepository.php
├── public/
│   ├── index.php
│   ├── create.php
│   └── products.php
└── storage/
    └── products.txt
```

## Como Executar

1. **Configurar XAMPP**
   - Copie o projeto para `C:\xampp\htdocs\products-srp-demo\`
   - Inicie o Apache no XAMPP

2. **Acessar o Sistema**
   - Abra o navegador em: `http://localhost/products-srp-demo/public/`

3. **Funcionalidades**
   - **Cadastro**: Formulário para cadastrar novos produtos
   - **Listagem**: Visualizar todos os produtos cadastrados

## Regras de Negócio

### Validações
- **Nome**: Obrigatório, entre 2 e 100 caracteres
- **Preço**: Obrigatório, numérico e maior ou igual a zero

### Persistência
- Dados salvos em `storage/products.txt`
- Formato: um JSON por linha
- ID incremental automático

## Arquitetura e SRP

### Separação de Responsabilidades

1. **ProductValidator** (Domain)
   - Responsabilidade: Validar dados de entrada
   - Não conhece persistência ou apresentação

2. **FileProductRepository** (Infra)
   - Responsabilidade: Persistir e recuperar dados
   - Única classe que acessa o arquivo

3. **ProductService** (Application)
   - Responsabilidade: Orquestrar operações de negócio
   - Não contém lógica de validação ou persistência

4. **Páginas Públicas**
   - Responsabilidade: Apresentação e captura de dados
   - Não contém regras de negócio

## Casos de Teste

### Caso 1: Cadastro Válido
**Entrada:**
- Nome: "Teclado"
- Preço: 120.50

**Resultado Esperado:**
- HTTP 200/302 (redirecionamento)
- Produto aparece na listagem com ID 1

### Caso 2: Nome Muito Curto
**Entrada:**
- Nome: "T"
- Preço: 50

**Resultado Esperado:**
- HTTP 422 (erro de validação)
- Mensagem: "Nome deve ter pelo menos 2 caracteres"
- Formulário preserva dados inseridos

### Caso 3: Preço Negativo
**Entrada:**
- Nome: "Mouse"
- Preço: -10

**Resultado Esperado:**
- HTTP 422 (erro de validação)
- Mensagem: "Preço deve ser maior ou igual a zero"

### Caso 4: Lista Vazia
**Condição:** Arquivo `storage/products.txt` vazio ou inexistente

**Resultado Esperado:**
- Página de listagem exibe "Nenhum produto cadastrado ainda"
- Link para cadastrar primeiro produto

### Caso 5: Múltiplos Cadastros
**Ações:**
1. Cadastrar "Teclado" - R$ 120,50
2. Cadastrar "Mouse" - R$ 45,00
3. Cadastrar "Monitor" - R$ 899,99

**Resultado Esperado:**
- Listagem mostra 3 produtos
- IDs sequenciais: 1, 2, 3
- Dados corretos em ordem de cadastro

## Testes Manuais

### 1. Teste de Cadastro Válido
```
1. Acesse http://localhost/products-srp-demo/public/
2. Preencha: Nome="Produto Teste", Preço=99.99
3. Clique em "Cadastrar Produto"
4. Verifique redirecionamento e mensagem de sucesso
5. Acesse "Ver Lista de Produtos"
6. Confirme presença do produto na tabela
```

### 2. Teste de Validação de Nome
```
1. Acesse http://localhost/products-srp-demo/public/
2. Preencha: Nome="A", Preço=50
3. Clique em "Cadastrar Produto"
4. Verifique mensagem de erro do nome
5. Confirme que o formulário preservou os dados
```

### 3. Teste de Validação de Preço
```
1. Acesse http://localhost/products-srp-demo/public/
2. Preencha: Nome="Produto", Preço=-10
3. Clique em "Cadastrar Produto"
4. Verifique mensagem de erro do preço
```

### 4. Teste de Lista Vazia
```
1. Delete o arquivo storage/products.txt
2. Acesse http://localhost/products-srp-demo/public/products.php
3. Verifique mensagem "Nenhum produto cadastrado ainda"
```

## Critérios de Aceite

- ✅ Projeto roda em `http://localhost/products-srp-demo/public/`
- ✅ ProductService não contém echo/I/O
- ✅ FileProductRepository é único que acessa arquivo
- ✅ SimpleProductValidator apenas valida
- ✅ Código segue PSR-12 e organização em camadas
- ✅ README contém instruções e casos de teste

## Tecnologias

- PHP 7.4+
- XAMPP (Apache)
- PSR-4 Autoloading
- JSON para persistência
