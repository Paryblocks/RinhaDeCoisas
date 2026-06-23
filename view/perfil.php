<?php
require_once __DIR__ . "/../model/usuario_model.php";
require_once __DIR__ . "/../model/lutador_model.php";

if(isset($_GET['id'])){
    $perfil = Usuario::buscarPorId($_GET['id']);
    $lutadores = Lutador::buscarPorDono($_GET['id']);
} 
require_once __DIR__ . "/../components/header.php"; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center mb-4">
            <h1>Perfil de <?= htmlspecialchars($perfil['nome']) ?></h1>
        </div>

        <?php if ($perfil['id'] == $_SESSION['usuario_id']): ?>
            <div class="col-md-6 bg-light p-4 rounded shadow-sm mb-5">
                <h3 class="mb-4 text-center">Seus Dados</h3>
                
                <form action="../controller/usuario_controller.php" method="post">
                    <input type="hidden" name="acao" value="atualizar">
                    
                    <div class="mb-3 text-start">
                        <label class="form-label">Nome:</label>
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($perfil['nome']) ?>" required/>
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($perfil['email']) ?>" required/>
                    </div>
                    
                    <div class="mb-3 text-start">
                        <label class="form-label">Nova Senha:</label>
                        <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para manter a atual"/>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3">Atualizar Dados</button>
                </form>

                <hr>

                <div class="d-flex gap-2 justify-content-center mt-3">
                    <a href="historico.php?tipo=compra" class="btn btn-outline-secondary w-50">
                        Histórico de Compras
                    </a>
                    <a href="historico.php?tipo=batalha" class="btn btn-outline-danger w-50">
                        Histórico de Batalhas
                    </a>
                </div>

                <hr>

                <a href="../controller/usuario_controller.php?acao=excluir&id=<?= $_SESSION['usuario_id']?>" 
                    class="btn w-100 mb-3 btn-outline-danger" 
                    onclick="return confirm('Tem certeza absoluta que deseja excluir sua conta? Esta ação não pode ser desfeita!');">
                    Excluir conta
                </a>

            </div>
        <?php endif; ?>

        <div class="col-12 mt-4">
            <h3 class="text-center mb-4">Lutadores Criados por este Usuário</h3>
            
            <?php if (empty($lutadores)): ?>
                <p class="text-center text-muted">Este usuário ainda não criou nenhum lutador.</p>
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
    </div>
</div>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>