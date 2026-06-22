<?php
require_once __DIR__ . "/../util/verifica_login.php";
require_once __DIR__ . "/../model/lutador_model.php";

if(isset($_REQUEST['acao'])){
    $acao = $_REQUEST['acao'];

    switch($acao){
    case 'cadastrar':
        $novoLutador = new Lutador($_POST['nome'], $_POST['descricao'], $_POST['ataque'], $_POST['defesa'], $_POST['velocidade'], $_POST['preco'], $_SESSION['usuario_id']);
        if ($novoLutador->salvar()) {
            header('location: ../view/loja.php');
            exit;
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