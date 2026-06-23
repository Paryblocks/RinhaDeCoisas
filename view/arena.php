<?php
require_once __DIR__ . "/../util/verifica_login.php";
require_once __DIR__ . "/../model/lutador_model.php";

$lutadores = Lutador::buscarDisponivel($_SESSION['usuario_id']);

require_once __DIR__ . "/../components/header.php"; 
?>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4 text-danger font-weight-bold">ARENA DE BATALHAS</h1>
        <p class="text-muted">Selecione seu campeão para desafiar um oponente aleatório e ganhar dinheiro!</p>
    </div>

    <?php if (empty($lutadores)): ?>
        <div class="alert alert-warning text-center">
            Você ainda não possui nenhum lutador contratado! Vá até a <a href="loja.php">Loja</a> para conseguir um.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($lutadores as $lutador): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-danger">
                        <div class="card-body text-center">
                            <h4 class="card-title text-primary"><?= htmlspecialchars($lutador['nome']) ?></h4>
                            <p class="card-text text-muted small"><?= htmlspecialchars($lutador['descricao']) ?></p>
                            <hr>
                            <div class="d-flex justify-content-around mb-3">
                                <span>ATK: <strong><?= $lutador['ataque'] ?></strong></span>
                                <span>DEF: <strong><?= $lutador['defesa'] ?></strong></span>
                                <span>VEL: <strong><?= $lutador['velocidade'] ?></strong></span>
                            </div>
                            <button onclick="iniciarBatalha(<?= $lutador['id'] ?>)" class="btn btn-danger w-100 btn-guerrear">
                                Enviar para o Combate
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalBatalha" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTitulo">O Confronto Começou!</h5>
            </div>
            <div class="modal-body text-center" id="modalConteudo">
                <div id="loading">
                    <div class="spinner-border text-danger my-3" role="status"></div>
                    <p class="fw-bold">Consultando o juiz da arena...</p>
                </div>
                <div id="resultado" class="d-none text-start">
                    <h3 id="resTitulo" class="text-center text-success mb-3"></h3>
                    <h5 class="text-muted text-center mb-4" id="resOponente"></h5>
                    <div class="p-3 bg-light rounded border border-secondary font-italic pre-scrollable mb-3" id="resNarracao" style="white-space: pre-line;"></div>
                    <div class="alert alert-success text-center fw-bold h5" id="resRecompensa"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary d-none" id="btnFecharModal" onclick="window.location.reload()">Voltar para a Arena</button>
            </div>
        </div>
    </div>
</div>

<script>
function iniciarBatalha(lutadorId) {
    const meuModal = new bootstrap.Modal(document.getElementById('modalBatalha'));
    meuModal.show();
    
    document.querySelectorAll('.btn-guerrear').forEach(b => b.disabled = true);

    fetch('../controller/batalha_controller.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: lutadorId })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('loading').classList.add('d-none');
        const boxResultado = document.getElementById('resultado');
        boxResultado.classList.remove('d-none');

        if (data.erro) {
            document.getElementById('resTitulo').innerText = "Erro na Arena";
            document.getElementById('resTitulo').className = "text-center text-danger";
            document.getElementById('resNarracao').innerText = data.erro;
            document.getElementById('resRecompensa').classList.add('d-none');
        } else {
            document.getElementById('resTitulo').innerText = data.resultado;
            document.getElementById('resOponente').innerText = "Inimigo enfrentado: " + data.oponente;
            document.getElementById('resNarracao').innerText = data.narracao;
            document.getElementById('resRecompensa').innerText = "Recompensa Adquirida: $ " + data.recompensa;
        }

        document.getElementById('btnFecharModal').classList.remove('d-none');
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro crítico ao processar o combate.');
    });
}
</script>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>