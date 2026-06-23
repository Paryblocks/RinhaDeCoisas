<?php
require_once __DIR__ . "/../util/verifica_login.php";
require_once __DIR__ . "/../model/compra_model.php";
require_once __DIR__ . "/../model/lutador_model.php";
require_once __DIR__ . "/../model/usuario_model.php";

if (!isset($_POST['lutador_id'])) {
    header('location: ../view/loja.php');
    exit;
}

$lutador_id = $_POST['lutador_id'];
$comprador_id = $_SESSION['usuario_id'];

$lutador = Lutador::buscarPorId($lutador_id);
if (!$lutador) {
    header('location: ../view/item.php?id=' . $lutador_id . '&erro=Lutador nao encontrado');
    exit;
}
$preco = $lutador['preco'];
if($preco > $_SESSION['saldo']) {
    header('location: ../view/item.php?id=' . $lutador_id . '&erro=Saldo insuficiente');
    exit;
}

$novaCompra = new Compra($comprador_id, $lutador_id, $preco);
if ($novaCompra->salvar()) {
    $novoSaldo = $_SESSION['saldo'] - $preco;
    if(Usuario::atualizarSaldo($comprador_id, $novoSaldo)){
        $_SESSION['saldo'] = $novoSaldo;
    }
    header('location: ../view/item.php?id=' . $lutador_id . '&sucesso');
    exit;
}
?>