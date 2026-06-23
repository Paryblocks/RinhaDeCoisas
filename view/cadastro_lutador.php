<?php
require_once __DIR__ . "/../util/verifica_login.php";
require_once __DIR__ . "/../components/header.php"; 
?>

<div class="row">
    <div class="col-12 text-center my-4">
        <h1>Crie seu Lutador!</h1>
        <form action="../controller/lutador_controller.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="cadastrar">
            <label>Nome:</label>
            <input type="text" name="nome" required/>
            <br><br>
            <label>Descrição:</label>
            <input type="text" name="descricao" required/>
            <br><br>
            <div>Pontos para distribuir: <strong id="pontos-restantes">9</strong></div>
            <br>
            <label>Ataque:</label>
            <input type="number" name="ataque" min="1" max="10" value="1" class="ponto-input" required/>
            <label>Defesa:</label>
            <input type="number" name="defesa" min="1" max="10" value="1" class="ponto-input" required/>
            <label>Velocidade:</label>
            <input type="number" name="velocidade" min="1" max="10" value="1" class="ponto-input" required/>
            <br><br>
            <div>Valor do Lutador: <strong id="valor-lutador">300</strong></div>
            <br>
            <button type="submit" id="btnEnviar">Cadastrar</button>
            <?php if(isset($_GET['erro'])){
                echo "<p style='color:red'>" . $_GET['erro'] . "</p>";
            } ?>
        </form>
    </div>
</div>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>

<script>
        const LIMITE_MAXIMO = 12;
        const inputs = document.querySelectorAll('.ponto-input');
        const txtRestantes = document.getElementById('pontos-restantes');
        const txtValor = document.getElementById('valor-lutador');
        const btnEnviar = document.getElementById('btnEnviar');

        function calcularPontos() {
            let somaTotal = 0;

            inputs.forEach(input => {
                somaTotal += parseInt(input.value) || 0;
            });
            let restantes = LIMITE_MAXIMO - somaTotal;
            txtRestantes.textContent = restantes;
            let precoLutador = somaTotal * 100;
            txtValor.textContent = precoLutador;

            if (restantes < 0) {
                txtRestantes.style.color = 'red';
                btnEnviar.disabled = true;
            } else {
                txtRestantes.style.color = 'black';
                btnEnviar.disabled = false;
            }
        }
        inputs.forEach(input => {
            input.addEventListener('input', calcularPontos);
        });

        calcularPontos();
    </script>