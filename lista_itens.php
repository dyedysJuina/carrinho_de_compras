<!DOCTYPE html>
<html>
<head>
    <title>Carrinho de Compras</title>
    <!-- Importação das bibliotecas do jQuery e Toastr (para exibir notificações) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <h1>Produtos Disponíveis</h1>
    <ul>
        <?php
        include_once("conexao.php");

        $sql = "SELECT * FROM produtos";
        $query = mysqli_query($conn, $sql);

        while($produto = mysqli_fetch_assoc($query)):
        ?>
        <!-- Lista de produtos com botões de adicionar ao carrinho -->
        <li>
            <?php echo $produto['nome']; ?> - R$ <?php echo number_format((float)$produto['preco'], 2, ',', '.'); ?>
            <button type="button" onclick="adicionarAoCarrinho(<?php echo $produto['idproduto']; ?>, '<?php echo $produto['nome']; ?>', <?php echo $produto['preco']; ?>)">Adicionar</button>
        </li>
        <?php endwhile; ?>
    </ul>

    <script>
        // Função para adicionar um produto ao carrinho através de AJAX
        function adicionarAoCarrinho(produtoId, produtoNome, produtoPreco) {
            var quantidade = 1; // Quantidade inicial do produto a ser adicionado

            // Criando um objeto JavaScript para representar o produto a ser adicionado
            var produto = {
                produto_id: produtoId,
                produto_nome: produtoNome,
                produto_preco: produtoPreco,
                quantidade: quantidade
            };

            // Chamada AJAX para enviar o produto para o script "carrinho.php"
            $.ajax({
                type: "POST",
                url: "carrinho.php",
                data: produto, // Dados do produto a serem enviados para o script
                success: function (response) {
                    // Função de sucesso: notifica o usuário sobre o resultado da adição
                    if (response.trim() === "sucesso") {
                        toastr.success("Produto adicionado ao carrinho com sucesso!", "Sucesso");
                    } else {
                        toastr.error("Erro ao adicionar produto: " + response, "Erro");
                    }
                },
                error: function (xhr, status, error) {
                    // Função de erro: notifica o usuário sobre o erro ocorrido
                    toastr.error("Erro ao adicionar produto: " + error, "Erro");
                }
            });
        }
    </script>
</body>
</html>
