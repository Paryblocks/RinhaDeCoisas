<?php
require_once __DIR__ . "/../model/usuario_model.php";

session_start();

if(isset($_REQUEST['acao'])){
    $acao = $_REQUEST['acao'];

    switch($acao){
    case 'cadastrar':
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            header('location: ../view/cadastro_usuario.php?erro=Email invalido.');
            exit;
        }
        $senha = hash('sha256', $_POST["senha"] . "asrt23"); // Um salt besta de exemplo
        $novoUsuario = new Usuario($_POST['nome'], $_POST['email'], $senha);
        $resposta = $novoUsuario->salvar();
        if ($resposta) {
            $_SESSION['usuario_id'] = $resposta;
            $_SESSION['nome'] = $_POST['nome'];
            $_SESSION['logado'] = true;
            $_SESSION['saldo'] = 500.0;
            session_regenerate_id(true);
            header('location: ../view/home.php');
            exit;
        } else {
            header('location: ../view/cadastro_usuario.php?erro=Erro ao criar conta. Tente outro email!');
            exit;
        }
        break;

    case 'excluir':
        if (Usuario::excluir($_SESSION['usuario_id'])) {
            $_SESSION = array();
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();

            header('location: ../view/home.php');
            exit;
        }
        break;

    case 'atualizar':
        if (!empty($_POST['senha'])) {
            $senha = hash('sha256', $_POST['senha'] . "asrt23");
        } else {
            $usuarioAtual = Usuario::buscarPorId($_SESSION['usuario_id']);
            $senha = $usuarioAtual['senha']; 
        }
        $updatedUsuario = new Usuario($_POST['nome'], $_POST['email'], $senha);
        if ($updatedUsuario->atualizar($_SESSION['usuario_id'])) {
            header('location: ../view/perfil.php');
            exit;
        }
        break;

    case 'login':
        $email = $_POST['email'];
        $senha = hash('sha256', $_POST['senha'] . "asrt23");

        if ($usuario = Usuario::login($email, $senha)) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['logado'] = true;
            $_SESSION['saldo'] = $usuario['saldo'];
            session_regenerate_id(true);
            header('location: ../view/home.php');
            exit;
        } else {
            header('location: ../view/login.php?erro=Email ou senha incorretos!');
            exit;
        }
        break;
    }
}
?>