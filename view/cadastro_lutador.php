<?php
require_once __DIR__ . "/../util/verifica_login.php";
require_once __DIR__ . "/../components/header.php"; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="card shadow-lg border-danger">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h2 class="mb-0 text-uppercase fw-bold text-danger">Crie seu Lutador!</h2>
                </div>
                
                <div class="card-body p-4">
                    <form action="../controller/lutador_controller.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="acao" value="cadastrar">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome do Campeão:</label>
                            <input type="text" name="nome" class="form-control form-control-lg border-secondary" placeholder="Ex: Goku, Mike Tyson, Uma Cadeira..." required/>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Descrição / Lore:</label>
                            <input type="text" name="descricao" class="form-control border-secondary" placeholder="Breve história ou conquistas do lutador" required/>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto do Lutador:</label>
                            <input type="file" id="foto" name="foto" accept="image/*" class="form-control border-secondary">
                            <div class="form-text">Deixe em branco para usar a imagem padrão.</div>
                        </div>

                        <hr class="text-danger my-4">

                        <div class="p-3 bg-light rounded border mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 text-secondary fw-bold">ATRIBUTOS</h5>
                                <div class="h6 mb-0">Pontos Restantes: <strong id="pontos-restantes" class="badge bg-dark fs-6">9</strong></div>
                            </div>
                            
                            <div class="row g-3">
                                <div class="col-4">
                                    <label class="form-label small fw-bold text-danger">ATAQUE</label>
                                    <input type="number" name="ataque" min="1" max="10" value="1" class="form-control text-center fw-bold border-danger punto-input ponto-input" required/>
                                </div>
                                <div class="col-4">
                                    <label class="form-label small fw-bold text-primary">DEFESA</label>
                                    <input type="number" name="defesa" min="1" max="10" value="1" class="form-control text-center fw-bold border-primary punto-input ponto-input" required/>
                                </div>
                                <div class="col-4">
                                    <label class="form-label small fw-bold text-warning">VELOCIDADE</label>
                                    <input type="number" name="velocidade" min="1" max="10" value="1" class="form-control text-center fw-bold border-warning punto-input ponto-input" required/>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted fw-bold">Valor de Contratação:</span>
                            <h3 class="mb-0 text-success fw-bold">$ <span id="valor-lutador">300</span></h3>
                        </div>

                        <button type="submit" id="btnEnviar" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm">
                            CONVOCAR PARA A RINHA
                        </button>

                        <?php if(isset($_GET['erro'])): ?>
                            <div class="alert alert-danger text-center mt-3 mb-0 py-2">
                                ⚠️ <?= htmlspecialchars($_GET['erro']) ?>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

        </div>
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
                txtRestantes.className = 'badge bg-danger fs-6';
                btnEnviar.disabled = true;
            } else {
                txtRestantes.className = 'badge bg-dark fs-6';
                btnEnviar.disabled = false;
            }
        }
        inputs.forEach(input => {
            input.addEventListener('input', calcularPontos);
        });

        calcularPontos();
</script>