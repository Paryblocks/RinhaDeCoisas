<?php
require_once __DIR__ . "/../util/verifica_login.php";
require_once __DIR__ . "/../model/compra_model.php";
require_once __DIR__ . "/../model/batalha_model.php";

$tipo = $_GET['tipo'] ?? 'compra'; 
$dados_historico = [];

if($tipo === 'compra'){
    $dados_historico = Compra::listarPorComprador($_SESSION['usuario_id']);
} elseif($tipo === 'batalha'){
    $dados_historico = Batalha::listarPorUsuario($_SESSION['usuario_id']);
}

require_once __DIR__ . "/../components/header.php"; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10 text-center mb-4">
            <h1>Seu Histórico</h1>
            <p class="text-muted">Acompanhe suas transações e conquistas na Arena</p>
            
            <div class="d-flex justify-content-center gap-2 my-4">
                <a href="historico.php?tipo=compra" class="btn <?= $tipo === 'compra' ? 'btn-primary' : 'btn-outline-primary' ?>">
                    Histórico de Compras
                </a>
                <a href="historico.php?tipo=batalha" class="btn <?= $tipo === 'batalha' ? 'btn-danger' : 'btn-outline-danger' ?>">
                    Histórico de Batalhas
                </a>
            </div>
        </div>

        <div class="col-md-10">
            <div class="card shadow-sm p-4 bg-white">
                <h3 class="mb-4 text-capitalize"><?= $tipo ?>s Realizadas</h3>

                <?php if (empty($dados_historico)): ?>
                    <p class="text-center text-muted py-4">Nenhum registro encontrado para este histórico.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            
                            <?php if ($tipo === 'compra'): ?>
                                <thead class="table-dark">
                                    <tr>
                                        <th>Lutador Adquirido</th>
                                        <th>Preço Pago</th>
                                        <th>Data da Compra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dados_historico as $item): ?>
                                        <tr>
                                            <td class="fw-bold text-primary"><?= htmlspecialchars($item['nome_lutador']) ?></td>
                                            <td><span class="badge bg-success">$ <?= number_format($item['valor'], 2, ',', '.') ?></span></td>
                                            <td><?= (new DateTime($item['data_compra']))->format('d/m/Y H:i') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            <?php else: ?>
                                <thead class="table-dark">
                                    <tr>
                                        <th>Data</th>
                                        <th class="text-center">Confronto</th>
                                        <th>Resultado</th>
                                        <th>Recompensa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dados_historico as $item): ?>
                                        <tr>
                                            <td><?= (new DateTime($item['data_batalha']))->format('d/m/Y H:i') ?></td>
                                            <td class="text-center">
                                                <span class="fw-bold text-primary"><?= htmlspecialchars($item['lutador_casa']) ?></span> 
                                                <span class="text-muted px-2 font-italic">VS</span> 
                                                <span class="fw-bold text-danger"><?= htmlspecialchars($item['lutador_fora']) ?></span>
                                            </td>
                                            
                                            <td>
                                                <?php if (strtolower($item['resultado']) === 'vitoria' || $item['resultado'] == 1): ?>
                                                    <span class="badge bg-success">Vitória</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Derrota</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="text-success fw-bold">$ <?= number_format($item['recompensa'], 2, ',', '.') ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            <?php endif; ?>

                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
require_once __DIR__ . "/../components/footer.php"; 
?>