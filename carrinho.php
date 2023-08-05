<?php
session_start();

// Verifica se o botão de limpar carrinho foi clicado
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['limpar_carrinho'])) {
    unset($_SESSION['carrinho']); // Limpa o carrinho removendo a variável de sessão
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['produto_id']) && isset($_POST['produto_nome']) && isset($_POST['produto_preco']) && isset($_POST['quantidade']) && is_numeric($_POST['quantidade']) && $_POST['quantidade'] > 0) {
        $produto = [
            'id' => $_POST['produto_id'],
            'nome' => $_POST['produto_nome'],
            'preco' => $_POST['produto_preco'],
            'quantidade' => (int)$_POST['quantidade'] // Convertendo para inteiro usando (int)
        ];

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $produtoJaExiste = false;
        foreach ($_SESSION['carrinho'] as $index => $item) {
            if ($item['id'] === $produto['id']) {
                $_SESSION['carrinho'][$index]['quantidade'] += $produto['quantidade'];
                $produtoJaExiste = true;
                break;
            }
        }

        if (!$produtoJaExiste) {
            $_SESSION['carrinho'][] = $produto;
        }

        echo "sucesso"; // Responde à requisição AJAX com a mensagem de sucesso
        exit; // Termina a execução para evitar renderização do HTML após a requisição AJAX
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Carrinho de Compras</h1>
    <?php if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0): ?>
        <ul>
            <?php
            $totalCarrinho = 0; // Variável para armazenar o valor total do carrinho

            foreach ($_SESSION['carrinho'] as $item):
                $subtotal = $item['preco'] * $item['quantidade'];
                $totalCarrinho += $subtotal;
            ?>


      <div class="limitador">
          
      <div class="cards">

        <div class="imagem">
            imagem
        </div>

        <div class="dados">
            <div class="titulo">
            <?php echo $item['nome']; ?>
            </div>
            <div class="dados_preco">
            <div class="valor_unitario">
             <p>Uni</p>        
            <?php echo"R$ ".number_format((float)$item['preco'], 2, ',', '.'); ?>
            </div>
            <div class="quantidade">
                <p>Qtd</p>
            <?php echo $item['quantidade']; ?>
            </div>
            <div class="valor_total">
                <p>Total</p>
            <?php echo"R$".number_format((float)$subtotal, 2, ',', '.'); ?>
            </div>
        </div>
        </div>
        </div>


      </div>


            





           <!-- <li>
                    <?php echo $item['nome']; ?> - R$ <?php echo number_format((float)$item['preco'], 2, ',', '.'); ?>
                    - QTD: <?php echo $item['quantidade']; ?> - Subtotal: R$ <?php echo number_format((float)$subtotal, 2, ',', '.'); ?>
                </li> -->
            </div>
                
            <?php endforeach; ?>
        </ul>

       

        <?php
        // Construir os parâmetros da mensagem
        $mensagem = "Olá! Aqui estão os itens do seu carrinho:%0A";
        foreach ($_SESSION['carrinho'] as $item) {
            $subtotal = $item['preco'] * $item['quantidade'];
            $mensagem .= $item['nome'] . " - Quantidade: " . $item['quantidade'] . " - Subtotal: R$" . number_format((float)$subtotal, 2, ',', '.') . "%0A";
        }?>
        
        
        
        <?php
        $mensagem .= "Total do Carrinho: R$ " . number_format((float)$totalCarrinho, 2, ',', '.') . "%0A";

        // Número de telefone para onde enviar a mensagem (substitua pelo número desejado)
        $numeroTelefone = "5544998331612";

        // Montar o link do WhatsApp Web
        $linkWhatsapp = "https://api.whatsapp.com/send?phone=" . $numeroTelefone . "&text=" . $mensagem;
        ?>
        
        <div class="botao_comprar">
            <div class="total">
            <h4>Total de</h4>
              <p><?php echo"R$".number_format((float)$totalCarrinho, 2, ',', '.'); ?></p> 
            </div>
            <div class="enviar_whatsapp">
              <a href="<?php echo $linkWhatsapp; ?>" tarGET="_blank">Enviar Pedido</a>
            </div>
        </div>

        

        <form method="GET">
            <button type="submit" name="limpar_carrinho">Limpar Carrinho</button>
        </form>
    <?php else: ?>
        <p>Carrinho vazio.</p>
    <?php endif; ?>
</body>
</html>
