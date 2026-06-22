<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['erro' => 'Sessão expirada. Faça login novamente.']);
    exit;
}

require_once __DIR__ . "/../util/conexao.php";
require_once __DIR__ . "/../model/batalha_model.php";
require_once __DIR__ . "/../model/lutador_model.php";
require_once __DIR__ . "/../model/usuario_model.php";

header('Content-Type: application/json');

$json_recebido = file_get_contents("php://input");
$dados = json_decode($json_recebido, true);

if (!$dados || !isset($dados['id'])) {
    echo json_encode(['erro' => 'Selecione seu lutador.']);
    exit;
}

$lutador_id = $dados['id']; 

$lutador_usuario = Lutador::buscarPorId($lutador_id);
$oponente = Lutador::buscarInimigoAleatorio($lutador_id);

if (!$lutador_usuario || !$oponente) {
    echo json_encode(['erro' => 'Erro ao preparar a arena. Verifique se há outros lutadores cadastrados.']);
    exit;
}

$apiKey = "Minha Key (Não vou vazar não kk)";
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

$prompt = "Você é o juiz supremo de uma arena de batalhas de anime. 
Analise os dois lutadores abaixo e decida quem vence a luta baseando-se nos atributos deles, mas adicione um fator imprevisível de sorte/roteiro (onde o mais fraco tem cerca de 20% de chance de vencer de forma hilária).

Lutador 1 (Jogador):
- Nome: {$lutador_usuario['nome']}
- Descrição: {$lutador_usuario['descricao']}
- Ataque: {$lutador_usuario['ataque']} | Defesa: {$lutador_usuario['defesa']} | Velocidade: {$lutador_usuario['velocidade']}

Lutador 2 (Inimigo):
- Nome: {$oponente['nome']}
- Descrição: {$oponente['descricao']}
- Ataque: {$oponente['ataque']} | Defesa: {$oponente['defesa']} | Velocidade: {$oponente['velocidade']}

REQUISITO OBRIGATÓRIO DE RESPOSTA: Você deve responder ESTRITAMENTE em formato JSON (sem markdown, sem ```json, apenas o texto puro do JSON) com as seguintes chaves:
{
  \"vencedor\": \"jogador\" ou \"inimigo\",
  \"resultado_texto\": \"Frase curta resumindo o resultado (ex: Vitoria de X)\",
  \"narracao\": \"Uma narração curta, épica e engraçada da luta em 2 ou 3 parágrafos.\"
}";

$jsonData = json_encode([
    "contents" => [["parts" => [["text" => $prompt]]]],
    "generationConfig" => ["responseMimeType" => "application/json"] 
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $responseArr = json_decode($response, true);
    $geminiResultTexto = $responseArr['candidates'][0]['content']['parts'][0]['text'];
    $resultadoIa = json_decode($geminiResultTexto, true);

    if ($resultadoIa) {
        $quemVenceu = $resultadoIa['vencedor'];
        $resultadoTexto = $resultadoIa['resultado_texto'];
        $narracao = $resultadoIa['narracao'];

        $recompensa = ($quemVenceu === 'jogador') ? 100.00 : 10.00;

        $batalha = new Batalha($resultadoTexto, $recompensa, $_SESSION['usuario_id']);
        $batalha_id = $batalha->salvar();

        if ($batalha_id) {
            $batalha->salvarParticipantes($lutador_id, $batalha_id, 'Jogador');
            $batalha->salvarParticipantes($oponente['id'], $batalha_id, 'Inimigo');
            
            $novoSaldo = $_SESSION['saldo'] + $recompensa;
            Usuario::atualizarSaldo($_SESSION['usuario_id'], $novoSaldo);
            $_SESSION['saldo'] = $novoSaldo;
        }

        echo json_encode([
            'sucesso' => true,
            'oponente' => $oponente['nome'],
            'resultado' => $resultadoTexto,
            'recompensa' => $recompensa,
            'narracao' => $narracao
        ]);
        exit;
    }
}

echo json_encode(['erro' => 'Os deuses da arena recusaram julgar esta luta. Tente novamente.']);
exit;
?>