<?php
require_once __DIR__ . "/../components/header.php"; 
?>

<div class="row">
    <div class="col-12 text-center my-4">
        <h1>Fale Conosco!</h1>
        <form action="../util/mailer.php" method="post" enctype="multipart/form-data">
            <label>Email:</label>
            <input type="email" name="email" required/>
            <br><br>
            <label>Assunto:</label>
            <input type="text" name="assunto" required/>
            <br><br>
            <textarea name="mensagem" id="mensagem" placeholder="Digite sua mensagem aqui!"  rows="4" cols="40" required></textarea>
            <br><br>
            <button type="submit">Enviar</button>
            <br>
            <?php if(isset($_GET['erro'])){
                echo "<p style='color:red'>" . $_GET['erro'] . "</p>";
            } ?>
            <?php if(isset($_GET['sucesso'])){
                echo "<p style='color:green'>" . $_GET['sucesso'] . "</p>";
            } ?>
        </form>
    </div>
</div>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>