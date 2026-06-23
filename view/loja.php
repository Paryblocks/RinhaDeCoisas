<?php
require_once __DIR__ . "/../model/lutador_model.php";
$lutadores = Lutador::listarTudo();

require_once __DIR__ . "/../components/header.php"; 
?>

<div class="col-12 mt-4">
    <h3 class="text-center mb-4">Loja de Lutadores</h3>
            
    <?php if (empty($lutadores)): ?>
        <p class="text-center text-muted">Nenhum lutador na arena?!? Vá criar alguns!</p>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($lutadores as $lutador): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= htmlspecialchars($lutador['nome']) ?></h5>
                            <p class="card-text text-muted text-truncate"><?= htmlspecialchars($lutador['descricao']) ?></p>
                                    
                            <div class="bg-light p-2 rounded mb-3 small">
                                <strong>ATK:</strong> <?= $lutador['ataque'] ?> | 
                                <strong>DEF:</strong> <?= $lutador['defesa'] ?> | 
                                <strong>VEL:</strong> <?= $lutador['velocidade'] ?>
                            </div>
                                    
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success fs-6">$ <?= number_format($lutador['preco'], 2, ',', '.') ?></span>
                                <a href="item.php?id=<?= $lutador['id'] ?>" class="btn btn-sm btn-outline-primary">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>