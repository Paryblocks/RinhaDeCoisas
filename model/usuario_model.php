<?php
require_once __DIR__ . "/../util/conexao.php";

class Usuario{
    private $id, $nome, $email, $senha, $saldo;

     public function __construct($nome, $email, $senha){
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
    }

    public function salvar(){
        $conn = getConnection();
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $this->senha);
        if ($stmt->execute()) {
            return $conn->lastInsertId();
        }
    }

    public static function buscarPorId(int $id) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function listarTudo() {
        $conn = getConnection();
        $stmt = $conn->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar(int $id) {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id");
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $this->senha);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public static function atualizarSaldo($id, $novoSaldo) {
        $conn = getConnection();
        $stmt = $conn->prepare("UPDATE usuarios SET saldo = :saldo WHERE id = :id");
        $stmt->bindParam(":saldo", $novoSaldo);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public static function excluir($id) {
        $conn = getConnection();
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public static function login($email, $senha) {
        $conn = getConnection();
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":senha", $senha);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

?>