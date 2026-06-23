<?php
require_once __DIR__ . "/../util/conexao.php";

class Compra{
    private $id, $comprador_id, $lutador_id, $data_compra, $valor;

     public function __construct($comprador_id, $lutador_id, $valor){
        $this->comprador_id = $comprador_id;
        $this->lutador_id = $lutador_id;
        $this->valor = $valor;
    }

    public function salvar(){
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO compras (comprador_id, lutador_id, valor) VALUES (:comprador_id, :lutador_id, :valor)");
        $stmt->bindParam(":comprador_id", $this->comprador_id);
        $stmt->bindParam(":lutador_id", $this->lutador_id);
        $stmt->bindParam(":valor", $this->valor);
        if ($stmt->execute()) {
            return $conn->lastInsertId();
        }
    }

    public static function buscarPorId($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM compras WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function verificarDono($comprador_id, $lutador_id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM compras WHERE comprador_id = :comprador_id AND lutador_id = :lutador_id");
        $stmt->bindParam(":comprador_id", $comprador_id);
        $stmt->bindParam(":lutador_id", $lutador_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function contarVendasParaOutros($lutador_id, $criador_id) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM compras WHERE lutador_id = :lutador_id AND comprador_id != :criador_id");
    $stmt->bindParam(":lutador_id", $lutador_id);
    $stmt->bindParam(":criador_id", $criador_id);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    return $resultado['total'];
}

    public static function listarPorComprador($comprador_id) {
        $conn = getConnection();
        $stmt = $conn->prepare(
            "SELECT c.*, l.nome AS nome_lutador 
            FROM compras c
            INNER JOIN lutadores l ON c.lutador_id = l.id
            WHERE c.comprador_id = :comprador_id
            ORDER BY c.id DESC");
        $stmt->bindParam(":comprador_id", $comprador_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>