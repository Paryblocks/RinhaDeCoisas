<?php
require_once __DIR__ . "/../util/conexao.php";

class Batalha{
    private $id, $data_batalha, $resultado, $recompensa, $usuario_id;

     public function __construct($resultado, $recompensa, $usuario_id){
        $this->resultado = $resultado;
        $this->recompensa = $recompensa;
        $this->usuario_id = $usuario_id;
    }

    public function salvar(){
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO batalhas (resultado, recompensa, usuario_id) VALUES (:resultado, :recompensa, :usuario_id)");
        $stmt->bindParam(":resultado", $this->resultado);
        $stmt->bindParam(":recompensa", $this->recompensa);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        if ($stmt->execute()) {
            return $conn->lastInsertId();
        }
    }

    public static function buscarPorId(int $id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM batalhas WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function listarTudo() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT * FROM batalhas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvarParticipantes($lutador_id, $batalha_id, $equipe){
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO lutador_batalha (lutador_id, batalha_id, equipe) VALUES (:lutador_id, :batalha_id, :equipe)");
        $stmt->bindParam(":lutador_id", $lutador_id);
        $stmt->bindParam(":batalha_id", $batalha_id);
        $stmt->bindParam(":equipe", $equipe);
        return $stmt->execute();
    }

    public static function listarParticipantes($batalha_id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM lutador_batalha WHERE batalha_id = :batalha_id");
        $stmt->bindParam(":batalha_id", $batalha_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>