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
                        <img src="../uploads/<?= $lutador['imagem'] ?>" class="card-img-top" alt="<?= htmlspecialchars($lutador['nome']) ?>" style="height: 200px; object-fit: cover;">
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
                
                <div id="loading" class="py-4">
                    <div class="spinner-border text-danger my-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <p class="fw-bold fs-5 text-muted">Consultando o juiz da arena...</p>
                </div>

                <div id="resultado" class="d-none text-start">
                    
                    <div class="row align-items-center justify-content-center bg-dark p-3 rounded shadow mb-4 text-white text-center g-2">
                        <div class="col-5 col-sm-4">
                            <img id="resImgJogador" src="" class="img-fluid rounded border border-danger shadow-sm mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                            <h5 id="resNomeJogador" class="text-danger fw-bold text-truncate small mb-0"></h5>
                        </div>
                        
                        <div class="col-2 col-sm-2">
                            <h2 class="fw-bold text-warning italic mb-0" style="font-style: italic; text-shadow: 0 0 10px rgba(255,193,7,0.5);">VS</h2>
                        </div>
                        
                        <div class="col-5 col-sm-4">
                            <img id="resImgOponente" src="" class="img-fluid rounded border border-primary shadow-sm mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                            <h5 id="resNomeOponente" class="text-primary fw-bold text-truncate small mb-0"></h5>
                        </div>
                    </div>

                    <h3 id="resTitulo" class="text-center text-success fw-bold mb-3"></h3>
                    <div class="p-3 bg-light rounded border border-secondary mb-3" id="resNarracao" style="white-space: pre-line; max-height: 250px; overflow-y: auto;"></div>
                    <div class="alert alert-success text-center fw-bold h5 mb-0" id="resRecompensa"></div>
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
            
            document.getElementById('resNomeJogador').closest('.row').classList.add('d-none');
        } else {
            document.getElementById('resImgJogador').src = "../uploads/" + data.img_jogador;
            document.getElementById('resNomeJogador').innerText = data.jogador;
            
            document.getElementById('resImgOponente').src = "../uploads/" + data.img_oponente;
            document.getElementById('resNomeOponente').innerText = data.oponente;

            document.getElementById('resTitulo').innerText = data.resultado;
            document.getElementById('resNarracao').innerText = data.narracao;
            document.getElementById('resRecompensa').innerText = "Recompensa Adquirida: $" + data.recompensa;
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