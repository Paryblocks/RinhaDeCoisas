<?php
require_once __DIR__ . "/../util/verifica_login.php";
require_once __DIR__ . "/../model/lutador_model.php";
require_once __DIR__ . "/../model/usuario_model.php";
require_once __DIR__ . "/../model/compra_model.php";

if (isset($_GET['id'])) {
    $lutador = Lutador::buscarPorId($_GET['id']);
    
    if (!$lutador) {
        header('location: loja.php?erro=Lutador nao encontrado');
        exit;
    }
    
    $donoOriginal = Usuario::buscarPorId($lutador['criador_id']);
    
    $jaComprou = Compra::verificarDono($_SESSION['usuario_id'], $lutador['id']);
    $ehDono = ($lutador['criador_id'] == $_SESSION['usuario_id']);
} else {
    header('location: loja.php');
    exit;
}

require_once __DIR__ . "/../components/header.php"; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        
        <div class="col-md-5 mb-4">
            <div class="card shadow">
                <img src="../uploads/<?= $lutador['imagem'] ?>" class="card-img-top" alt="<?= htmlspecialchars($lutador['nome']) ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h2 class="card-title text-primary"><?= htmlspecialchars($lutador['nome']) ?></h2>
                    <a class="text-muted" href="perfil.php?id=<?= $lutador['criador_id']?>">Criado por: <?= htmlspecialchars($donoOriginal['nome']) ?></a>
                    <hr>
                    <p class="card-text"><?= htmlspecialchars($lutador['descricao']) ?></p>
                    
                    <div class="bg-light p-3 rounded mb-3">
                        <h5>Atributos Atuais:</h5>
                        <strong>Ataque:</strong> <?= $lutador['ataque'] ?><br>
                        <strong>Defesa:</strong> <?= $lutador['defesa'] ?><br>
                        <strong>Velocidade:</strong> <?= $lutador['velocidade'] ?>
                    </div>
                </div>
            </div>
            <br>
            <?php if ($ehDono): ?>
                <a href="../controller/lutador_controller.php?acao=excluir&id=<?= $lutador['id']?>" 
                    class="btn w-100 mb-3 btn-outline-danger" 
                    onclick="return confirm('Tem certeza absoluta que deseja excluir esse lutador? Esta ação não pode ser desfeita!');">
                    Excluir lutador
                </a>
            <?php endif; ?>
            <br>
            <?php if(isset($_GET['erro'])){
                echo "<p style='color:red'>" . $_GET['erro'] . "</p>";
            } ?>
        </div>

        <div class="col-md-5">
            <?php if ($ehDono): ?>
                <div class="p-4 border rounded bg-light shadow-sm">
                    <h4 class="text-success mb-3">Painel de Treinamento e Edição</h4>
                    <p class="small text-muted">Como criador desse lutador, você pode modificá-lo e treiná-lo.</p>
                    
                    <form action="../controller/lutador_controller.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="acao" value="atualizar">
                        <input type="hidden" name="id" value="<?= $lutador['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Nome do Lutador:</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($lutador['nome']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Descrição:</label>
                            <input type="text" name="descricao" class="form-control" value="<?= htmlspecialchars($lutador['descricao']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto do Lutador</label>
                            <input type="file" id="foto" name="foto" accept="image/*" class="form-control">
                        </div>

                        <div class="mb-3 font-weight-bold">
                            Pontos de Treinamento Restantes: <strong id="treino-restantes">0</strong> / 27
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ataque:</label>
                            <input type="number" name="ataque" class="form-control treino-input" min="<?= $lutador['ataque'] ?>" max="10" value="<?= $lutador['ataque'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Defesa:</label>
                            <input type="number" name="defesa" class="form-control treino-input" min="<?= $lutador['defesa'] ?>" max="10" value="<?= $lutador['defesa'] ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Velocidade:</label>
                            <input type="number" name="velocidade" class="form-control treino-input" min="<?= $lutador['velocidade'] ?>" max="10" value="<?= $lutador['velocidade'] ?>">
                        </div>

                        <div class="mb-3">
                            Preço de Treinamento: $ <strong id="novo-valor-lutador">0</strong>
                        </div>

                        <button type="submit" id="btnSalvarTreino" class="btn btn-success w-100">Salvar Alterações e Treino</button>
                    </form>
                </div>

            <?php else: ?>
                <?php if (!$jaComprou): ?>
                    <div class="p-4 border rounded bg-white text-center shadow-sm h-100 d-flex flex-column justify-content-center">
                        <h4 class="mb-3">Adquira este Lutador</h4>
                        <p class="text-muted">Adicione este guerreiro à sua coleção para usá-lo na Arena de Batalhas.</p>
                        
                        <div class="display-5 my-3 text-success font-weight-bold">
                            $ <?= number_format($lutador['preco'], 2, ',', '.') ?>
                        </div>

                        <form action="../controller/compra_controller.php" method="post">
                            <input type="hidden" name="acao" value="comprar">
                            <input type="hidden" name="lutador_id" value="<?= $lutador['id'] ?>">
                            
                            <?php if ($_SESSION['saldo'] >= $lutador['preco']): ?>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Comprar Agora</button>
                            <?php else: ?>
                                <button type="button" class="btn btn-secondary btn-lg w-100" disabled>Saldo Insuficiente</button>
                                <small class="text-danger mt-2 d-block">Você precisa de mais $ <?= number_format($lutador['preco'] - $_SESSION['saldo'], 2, ',', '.') ?></small>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="p-4 border rounded bg-white text-center shadow-sm h-100 d-flex flex-column justify-content-center">
                        <h4 class="mb-3">Você já possuí este lutador!</h4>
                        <p class="text-muted">Leve ele para a Arena de Batalhas e conquiste mais dinheiro!</p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>

<script>
    const MAXIMO_TREINO = 27; 
    const inputsTreino = document.querySelectorAll('.treino-input');
    const txtTreinoRestantes = document.getElementById('treino-restantes');
    const txtNovoValor = document.getElementById('novo-valor-lutador');
    const btnSalvarTreino = document.getElementById('btnSalvarTreino');

    function calcularTreino() {
        let somaTotal = 0;

        inputsTreino.forEach(input => {
            somaTotal += parseInt(input.value) || 0;
        });

        let restantes = MAXIMO_TREINO - somaTotal;
        txtTreinoRestantes.textContent = restantes;
        
        txtNovoValor.textContent = (somaTotal * 100) - <?= (float)$lutador['preco'] ?>;

        if (restantes < 0) {
            txtTreinoRestantes.style.color = 'red';
            btnSalvarTreino.disabled = true;
        } else {
            txtTreinoRestantes.style.color = 'black';
            btnSalvarTreino.disabled = false;
        }
    }

    inputsTreino.forEach(input => {
        input.addEventListener('input', calcularTreino);
    });

    calcularTreino();
</script>