<?php
require_once __DIR__ . "/../util/verifica_login.php";
require_once __DIR__ . "/../model/lutador_model.php";
require_once __DIR__ . "/../model/compra_model.php";
require_once __DIR__ . "/../model/usuario_model.php";

if(isset($_REQUEST['acao'])){
    $acao = $_REQUEST['acao'];

    switch($acao){
    case 'cadastrar':
        $soma_pontos = $_POST['ataque'] + $_POST['defesa'] + $_POST['velocidade'];
        if ($soma_pontos > 12) {
            header('location: ../view/cadastro_lutador.php?erro=Limite de pontos (12) excedido!');
            exit;
        }
    
        $preco = $soma_pontos * 100;
        if ($preco > $_SESSION['saldo']) {
            header('location: ../view/cadastro_lutador.php?erro=Dinheiro Insuficiente para criar esse lutador!');
            exit;
        }

        $nome_imagem = "padrao.png";
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $arquivo = $_FILES['foto'];
            
            $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
            $nome_imagem = md5(uniqid(rand(), true)) . "." . $extensao;
            $pasta_destino = __DIR__ . "/../uploads/";
            
            if (!is_dir($pasta_destino)) {
                mkdir($pasta_destino, 0777, true);
            }
            move_uploaded_file($arquivo['tmp_name'], $pasta_destino . $nome_imagem);
        }

        $novoLutador = new Lutador($_POST['nome'], $_POST['descricao'], $_POST['ataque'], $_POST['defesa'], $_POST['velocidade'], $preco, $_SESSION['usuario_id'], $nome_imagem);
        $idLutador = $novoLutador->salvar();
        if ($idLutador) {
            $novaCompra = new Compra($_SESSION['usuario_id'], $idLutador, $preco);
            if($novaCompra->salvar()){
                $novoSaldo = $_SESSION['saldo'] - $preco;
                if(Usuario::atualizarSaldo($_SESSION['usuario_id'], $novoSaldo)){
                    $_SESSION['saldo'] = $novoSaldo;
                }
                header('location: ../view/loja.php');
                exit;
            } 
        }
        break;

    case 'excluir':
        $id = $_GET['id'];
        $lutador = Lutador::buscarPorId($id);
        if ($lutador) {
            if ($lutador['criador_id'] != $_SESSION['usuario_id']) {
                header("location: ../view/loja.php?erro=Você não tem permissão para deletar este lutador!");
                exit;
            }

            $vendasParaOutros = Compra::contarVendasParaOutros($id, $lutador['criador_id']);

            if ($vendasParaOutros > 0) {
                header("location: ../view/item.php?id={$id}&erro=Este lutador já foi comprado por outros jogadores e não pode ser deletado!");
                exit;
            }
            
            if (Lutador::excluir($id)) {
                header("location: ../view/loja.php?sucesso=Lutador deletado com sucesso!");
                exit;
            } else {
                header("location: ../view/item.php?id={$id}&erro=Erro ao deletar o lutador.");
                exit;
            }
        } else {
            header("location: ../view/loja.php?erro=Lutador não encontrado.");
            exit;
        }
        break;

    case 'atualizar':
        $id = $_POST['id'];
        $ataque = $_POST['ataque'];
        $defesa = $_POST['defesa'];
        $velocidade = $_POST['velocidade'];

        $valor_antigo = Lutador::buscarPorId($id);

        if ($valor_antigo) {
            if ($ataque < $valor_antigo['ataque'] || 
                $defesa < $valor_antigo['defesa'] || 
                $velocidade < $valor_antigo['velocidade']) {
                
                header("location: ../view/item.php?id={$id}&erro=Não é permitido diminuir os atributos de um lutador!");
                exit;
            }

            $soma_pontos = $ataque + $defesa + $velocidade;
            if ($soma_pontos > 27) {
                header("location: ../view/item.php?id={$id}&erro=Limite de treino excedido!");
                exit;
            }

            $novo_preco = $soma_pontos * 100;
            $custo = $novo_preco - $valor_antigo['preco']; 
            
            if ($custo > 0 && $custo > $_SESSION['saldo']) {
                header("location: ../view/item.php?id={$id}&erro=Dinheiro Insuficiente para o treinamento!");
                exit;
            }

            $nome_imagem = $valor_antigo['imagem'];

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $arquivo = $_FILES['foto'];
                
                $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
                $nova_imagem = md5(uniqid(rand(), true)) . "." . $extensao;
                $pasta_destino = __DIR__ . "/../uploads/";
                
                if (!is_dir($pasta_destino)) {
                    mkdir($pasta_destino, 0777, true);
                }
                move_uploaded_file($arquivo['tmp_name'], $pasta_destino . $nova_imagem);

                if ($nome_imagem !== 'padrao.png' && !empty($nome_imagem)) {
                    $caminho_foto_antiga = $pasta_destino . $nome_imagem;
                    
                    if (file_exists($caminho_foto_antiga)) {
                        unlink($caminho_foto_antiga);
                    }
                }
                $nome_imagem = $nova_imagem;
            }

        } else {
            header("location: ../view/loja.php?erro=Lutador não encontrado.");
            exit;
        }

        $lutadorAtualizado = new Lutador($_POST['nome'], $_POST['descricao'], $ataque, $defesa, $velocidade, $novo_preco, $_SESSION['usuario_id'], $nome_imagem);
        if ($lutadorAtualizado->atualizar($id)) {
            if ($custo > 0) {
                $novoSaldo = $_SESSION['saldo'] - $custo;
                if (Usuario::atualizarSaldo($_SESSION['usuario_id'], $novoSaldo)) {
                    $_SESSION['saldo'] = $novoSaldo;
                }
            }
            header("location: ../view/item.php?id={$id}&sucesso=Lutador atualizado e treinado!");
            exit;
        } else {
            header("location: ../view/item.php?id={$id}&erro=Erro ao atualizar no banco.");
            exit;
        }
        break;
    }
}
?>