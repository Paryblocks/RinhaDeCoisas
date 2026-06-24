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

require_once __DIR__ . '/../util/config_env.php';

header('Content-Type: application/json');

$json_recebido = file_get_contents("php://input");
$dados = json_decode($json_recebido, true);

if (!$dados || !isset($dados['id'])) {
    echo json_encode(['erro' => 'Selecione seu lutador.']);
    exit;
}

$lutador_id = $dados['id']; 

$lutador_usuario = Lutador::buscarPorId($lutador_id);
$oponente = Lutador::buscarInimigo($lutador_id);

if (!$lutador_usuario || !$oponente) {
    echo json_encode(['erro' => 'Erro ao preparar a arena. Verifique se há outros lutadores cadastrados.']);
    exit;
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent";

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

O seu retorno deve ser ESTRITAMENTE um objeto JSON no seguinte formato:
```json
{
  \"vencedor\": \"jogador\" ou \"inimigo\",
  \"resultado_texto\": \"Frase curta resumindo o resultado (ex: Vitoria de X)\",
  \"narracao\": \"Uma narração curta, épica e engraçada da luta em 2 ou 3 parágrafos.\"
}
```";

$corpo = [
    "contents" => [
        [
            "parts" => [
                ["text" => $prompt]
            ]
        ]
    ]
];

$header = [
    "Content-Type: application/json",
    "x-goog-api-key: " . GEMINI_KEY
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($corpo));
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['erro' => 'Erro na requisição externa.']);
    exit;
} else {
    if ($response) {
        $result = json_decode($response, true);
        
        if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            echo json_encode([
                'erro' => 'Juiz tá de férias e não quis julgar esse luta.',
                'detalhes' => $result
            ]);
            exit;
        }

        $final = $result['candidates'][0]['content']['parts'][0]['text'];

        $final = rtrim($final, '```');
        $final = ltrim($final, '```json');
        $final = trim($final);

        $respostaGemini = json_decode($final, true);

        if ($respostaGemini) {
            $quemVenceu = $respostaGemini['vencedor'];
            $resultadoTexto = $respostaGemini['resultado_texto'];
            $narracao = $respostaGemini['narracao'];

            $recompensa = ($quemVenceu === 'jogador') ? 300.00 : 100.00;

            $batalha = new Batalha($quemVenceu, $recompensa, $_SESSION['usuario_id']);
            $batalha_id = $batalha->salvar();

            if ($batalha_id) {
                $batalha->salvarParticipantes($lutador_id, $batalha_id, 'Jogador');
                $batalha->salvarParticipantes($oponente['id'], $batalha_id, 'Oponente');
                
                $novoSaldo = $_SESSION['saldo'] + $recompensa;
                Usuario::atualizarSaldo($_SESSION['usuario_id'], $novoSaldo);
                $_SESSION['saldo'] = $novoSaldo;
            }
            echo json_encode([
                'sucesso' => true,
                'jogador' => $lutador_usuario['nome'],
                'img_jogador' => $lutador_usuario['imagem'],
                'oponente' => $oponente['nome'],
                'img_oponente' => $oponente['imagem'],
                'resultado' => $resultadoTexto,
                'recompensa' => $recompensa,
                'narracao' => $narracao
            ]);
            exit;
        }
    }
}

echo json_encode(['erro' => 'Juiz tá de férias e não quis julgar esse luta.']);
exit;
?>