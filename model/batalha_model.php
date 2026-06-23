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

    public static function listarPorUsuario($usuario_id) {
        $conn = getConnection();
        $stmt = $conn->prepare(
            "SELECT 
            b.id AS batalha_id,
            b.data_batalha,
            b.resultado,
            b.recompensa,
            l1.nome AS lutador_casa,
            l2.nome AS lutador_fora
            FROM batalhas b
            INNER JOIN lutador_batalha lb1 ON b.id = lb1.batalha_id AND lb1.equipe = 'Jogador'
            INNER JOIN lutadores l1 ON lb1.lutador_id = l1.id
            INNER JOIN lutador_batalha lb2 ON b.id = lb2.batalha_id AND lb2.equipe = 'Oponente'
            INNER JOIN lutadores l2 ON lb2.lutador_id = l2.id
            WHERE b.usuario_id = :usuario_id
            ORDER BY b.id DESC");        
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();
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