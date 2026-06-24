<?php
require_once __DIR__ . "/../util/conexao.php";

class Lutador{
    private $id, $nome, $descricao, $ataque, $defesa, $velocidade, $preco, $criador_id, $imagem;

     public function __construct($nome, $descricao, $ataque, $defesa, $velocidade, $preco, $criador_id, $imagem){
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->ataque = $ataque;
        $this->defesa = $defesa;
        $this->velocidade = $velocidade;
        $this->preco = $preco;
        $this->criador_id = $criador_id;
        $this->imagem = $imagem;
    }

    public function salvar(){
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO lutadores (nome, descricao, ataque, defesa, velocidade, preco, criador_id, imagem) VALUES (:nome, :descricao, :ataque, :defesa, :velocidade, :preco, :criador_id, :imagem)");
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":ataque", $this->ataque);
        $stmt->bindParam(":defesa", $this->defesa);
        $stmt->bindParam(":velocidade", $this->velocidade);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":criador_id", $this->criador_id);
        $stmt->bindParam(":imagem", $this->imagem);
        if ($stmt->execute()) {
            return $conn->lastInsertId();
        }
    }

    public static function buscarPorId($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM lutadores WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function buscarPorDono($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM lutadores WHERE criador_id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarDisponivel($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT l.*, l.id as id FROM lutadores l INNER JOIN compras c ON l.id = c.lutador_id WHERE c.comprador_id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarInimigo($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM lutadores WHERE id != :id ORDER BY RAND() LIMIT 1");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function buscarVs() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT * FROM lutadores ORDER BY RAND() LIMIT 2");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarTudo() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT * FROM lutadores");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE lutadores SET nome = :nome, descricao = :descricao, ataque = :ataque, defesa = :defesa, velocidade = :velocidade, preco = :preco, imagem = :imagem WHERE id = :id");
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":ataque", $this->ataque);
        $stmt->bindParam(":defesa", $this->defesa);
        $stmt->bindParam(":velocidade", $this->velocidade);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":imagem", $this->imagem);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public static function excluir($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM lutadores WHERE id = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

}

?>