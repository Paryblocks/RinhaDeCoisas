<?php
require_once "../model/usuario_model.php";

session_start();

if(isset($_REQUEST['acao'])){
    $acao = $_REQUEST['acao'];

    switch($acao){
    case 'cadastrar':
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            header('location: ../view/cadastro_usuario.php?erro=Email invalido');
            exit;
        }
        $senha = hash('sha256', $_POST["senha"] . "asrt23"); // Um salt besta de exemplo
        $novoUsuario = new Usuario($_POST['nome'], $_POST['email'], $senha, NULL);
        if ($novoUsuario->salvar()) {
            header('location: ../view/home.php');
            exit;
        }
        break;

    case 'excluir':
        if (Usuario::excluir($_SESSION['usuario_id'])) {
            header('location: ../view/home.php');
            exit;
        }
        break;

    case 'atualizar':
        $senha = hash('sha256', $_POST["senha"] . "asrt23");
        $updatedUsuario = new Usuario($_POST['nome'], $_POST['email'], $senha, $_SESSION['saldo']);
        if ($updatedUsuario->atualizar($_SESSION['usuario_id'])) {
            header('location: ../view/perfil.php');
            exit;
        }
        break;
    }
}
?>