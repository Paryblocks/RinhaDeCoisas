<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

require_once __DIR__ . '/../util/mail_env.php';

$mail = new PHPMailer(true);

$email = $_POST['email'];
$assunto = $_POST['assunto'];
$mensagem = $_POST['mensagem'];

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

try
{
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Username   = '2024001428@aluno.canoas.ifrs.edu.br';
    $mail->Password   = MAIL_PASSWORD;

    $mail->SMTPSecure = 'tls';
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;

    $mail->setFrom("2024001428@aluno.canoas.ifrs.edu.br", 'Rinha de Coisas');

    $mail->addReplyTo($email, 'Visitante');

    $mail->addAddress('2024001428@aluno.canoas.ifrs.edu.br', 'Admin');

    $mail->isHTML(true);
    $mail->Subject = "Fale Conosco: " . $assunto;

    $mail->Body    = "
        <h3>Nova mensagem recebida no Fale Conosco!</h3>
        <p><b>Enviado por:</b> {$email}</p>
        <p><b>Assunto:</b> {$assunto}</p>
        <p><b>Mensagem:</b><br>" . nl2br(htmlspecialchars($mensagem)) . "</p>
    ";
    
    $mail->AltBody = "Nova mensagem de {$email}: {$mensagem}";

    $mail->send();
    header("Location: ../view/fale_conosco.php?sucesso=Sua mensagem foi enviada com sucesso!");
}
catch (Exception $e)
{
    echo "<h3>Erro Fatal do PHPMailer:</h3>";
    echo "Texto do erro: " . $mail->ErrorInfo . "<br>";
    echo "Mensagem de exceção: " . $e->getMessage();
    exit;
    header("Location: ../view/fale_conosco.php?erro=Erro ao enviar mensagem.");
}