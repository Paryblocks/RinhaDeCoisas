<?php
require_once __DIR__ . "/../model/lutador_model.php";

$dupla = Lutador::buscarVs();

require_once __DIR__ . "/../components/header.php"; 
?>

<div class="row">
    <div class="col-12 text-center my-4">
        <h1>Bem-vindo a Rinha de Coisas!</h1>
        <p class="lead">Compre, venda e bote seus lutadores (por mais malucos que sejam) para brigar!</p>
        <hr>
        <p>Para começar, crie uma conta na plataforma! Após isso compre um lutador para levar para a arena ou crie o seu próprio para os demais comprarem!</p>
        <p>Ganhe dinheiro lutando na arena ou vendendo seus lutadores mais criativos na loja!</p>
        <p>Continue expandindo seu elenco de lutadores com o dinheiro adquirido, eles podem ser qualquer coisa, desde uma cadeira até um megazord!</p>

        <div class="container my-5 text-center">
            <?php if (count($dupla) < 2): ?>
                <p class="text-muted">Cadastre pelo menos 2 lutadores para ver o VS na Home!</p>
            <?php else: 
                $l1 = $dupla[0];
                $l2 = $dupla[1];
            ?>
                <div class="row align-items-center justify-content-center">
                    
                    <div class="col-md-4">
                        <img src="../uploads/<?= $l1['imagem'] ?>" class="img-fluid rounded border border-danger mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 class="text-danger"><?= htmlspecialchars($l1['nome']) ?></h4>
                    </div>

                    <div class="col-md-2 my-3 my-md-0">
                        <h1 class="display-3 fw-bold italic animate__animated animate__pulse animate__infinite">VS</h1>
                    </div>

                    <div class="col-md-4">
                        <img src="../uploads/<?= $l2['imagem'] ?>" class="img-fluid rounded border border-primary mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 class="text-primary"><?= htmlspecialchars($l2['nome']) ?></h4>
                    </div>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>