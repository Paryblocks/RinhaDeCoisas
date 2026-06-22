<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="plagiarism" content="Trabalho da disciplina de Desenvolvimento Web 2 do Instituto Federal de Educação, Ciência e Tecnologia do Rio Grande do Sul - Campus Canoas, desenvolvido por Matheus Steiner Silva">
    <title>Rinha de Coisas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php">Rinha de Coisas</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="loja.php">Comprar Lutadores</a></li>
                <li class="nav-item"><a class="nav-link" href="cadastro_lutador.php">Cadastrar Lutador</a></li>
                <li class="nav-item"><a class="nav-link" href="arena.php">Arena de Batalha</a></li>
                <li class="nav-item"><a class="nav-link" href="fale_conosco.php">Fale Conosco</a></li>
                <li class="nav-item"><a class="nav-link" href="termos_servico.php">Termos e Plágio</a></li>
            </ul>
            <div class="d-flex text-white align-items-center">
                <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                    <a href="../util/logout.php" class="btn" style="color: white;">Logout</a>
                    <span class="me-3">Saldo: <?php echo $_SESSION['saldo'] ?></span>
                    <a href="perfil.php?id=<?= $_SESSION['usuario_id'] ?>" class="btn btn-outline-light btn-sm">Olá, <?php echo htmlspecialchars($_SESSION['nome']) ?>!</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light btn-sm" style="margin-right: 10px;">Fazer Login</a>
                    <a href="cadastro_usuario.php" class="btn btn-outline-light btn-sm">Cadastrar-se</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">