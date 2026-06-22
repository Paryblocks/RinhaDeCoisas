<?php
require_once __DIR__ . "/../components/header.php"; 
?>

<div class="row">
    <div class="col-12 text-center my-4">
        <h1>Acesse sua Conta</h1>
        <form action="../controller/usuario_controller.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="login">
            <label>Email:</label>
            <input type="email" name="email" required/>
            <br><br>
            <label>Senha:</label>
            <input type="password" name="senha" required/>
            <br><br>
            <button type="submit">Login</button>
            <?php if(isset($_GET['erro'])){
                echo "<p style='color:red'>" . $_GET['erro'] . "</p>";
            } ?>
        </form>
    </div>
</div>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>