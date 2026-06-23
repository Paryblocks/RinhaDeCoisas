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

        $novoLutador = new Lutador($_POST['nome'], $_POST['descricao'], $_POST['ataque'], $_POST['defesa'], $_POST['velocidade'], $preco, $_SESSION['usuario_id']);
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
        if (Lutador::excluir($_GET['id'])) {
            header('location: ../view/loja.php');
            exit;
        }
        break;

    case 'atualizar':
        $updatedLutador = new Lutador($_POST['nome'], $_POST['descricao'], $_POST['ataque'], $_POST['defesa'], $_POST['velocidade'], $_POST['preco'], $_SESSION['usuario_id']);
        if ($updatedLutador->atualizar($_GET['id'])) {
            header('location: ../view/perfil.php');
            exit;
        }
        break;
    }
}
?>