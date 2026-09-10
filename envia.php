<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$phpmailerFiles = [
    'PHPMailer/Exception.php',
    'PHPMailer/PHPMailer.php',
    'PHPMailer/SMTP.php'
];

foreach ($phpmailerFiles as $file) {
    if (!file_exists($file)) {
        http_response_code(500);
        echo "Erro crítico: O arquivo $file não foi encontrado. Confira se a pasta no servidor se chama exatamente 'phpmailer' em minúsculas.";
        exit;
    }
    require $file;
}

function carregarEnv($caminho) {
    if (!file_exists($caminho)) {
        return false;
    }
    
    $linhas = @file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($linhas === false) {
        return false;
    }

    foreach ($linhas as $linha) {
        $linhaTrim = trim($linha);
        
        if ($linhaTrim === '' || substr($linhaTrim, 0, 1) === '#') {
            continue;
        }
        
        if (strpos($linhaTrim, '=') === false) {
            continue;
        }
        
        $partes = explode('=', $linhaTrim, 2);
        $nome = trim($partes[0]);
        $valor = isset($partes[1]) ? trim($partes[1], " \t\n\r\0\x0B\"'") : '';
        
        if ($nome !== '') {
            putenv("$nome=$valor");
            $_ENV[$nome] = $valor;
            $_SERVER[$nome] = $valor;
        }
    }
    return true;
}

carregarEnv(__DIR__ . '/.env');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['website_trap'])) {
        exit;
    }

    $nome = trim(strip_tags($_POST["nome"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $tipo_projeto = trim(strip_tags($_POST["tipo_projeto"] ?? ''));
    $prazo_desejado = trim(strip_tags($_POST["prazo_desejado"] ?? ''));
    $mensagem = trim(strip_tags($_POST["mensagem"] ?? ''));

    $erros = [];

    if (empty($nome)) { $erros[] = "O campo nome é obrigatório."; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $erros[] = "O e-mail informado é inválido."; }
    if (empty($tipo_projeto)) { $erros[] = "Selecione um tipo de projeto válido."; }
    if (empty($prazo_desejado)) { $erros[] = "Selecione o prazo desejado."; }
    if (empty($mensagem)) { $erros[] = "A mensagem não pode estar vazia."; }

    if (!empty($erros)) {
        foreach ($erros as $erro) {
            echo "<p style='color: #ef4444; font-family: sans-serif; margin-bottom: 8px;'>$erro</p>";
        }
        echo "<br><a href='javascript:history.back()' style='font-family: sans-serif; color: #7c3aed;'>← Voltar</a>";
        exit;
    }

    $smtpUser = $_ENV['SMTP_USER'] ?? getenv('SMTP_USER') ?: '';
    $smtpPass = $_ENV['SMTP_PASS'] ?? getenv('SMTP_PASS') ?: '';
    $mailTo = $_ENV['MAIL_TO'] ?? getenv('MAIL_TO') ?: '';

    if (empty($smtpUser) || empty($smtpPass)) {
        http_response_code(500);
        echo "Erro de configuração: As credenciais do .env não foram lidas corretamente.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUser;
        $mail->Password   = $smtpPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($smtpUser, 'Portfólio - Contato');
        $mail->addAddress($mailTo, 'Marianny');
        
        $mail->addReplyTo($email, $nome);

        $mail->isHTML(true);
        $mail->Subject = "Novo Projeto: " . htmlspecialchars($tipo_projeto);
        $mail->Body    = "<h3>Novo contato recebido pelo portfólio</h3>" .
                         "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>" .
                         "<p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>" .
                         "<p><strong>Tipo de Projeto:</strong> " . htmlspecialchars($tipo_projeto) . "</p>" .
                         "<p><strong>Prazo Desejado:</strong> " . htmlspecialchars($prazo_desejado) . "</p>" .
                         "<p><strong>Mensagem:</strong><br>" . nl2br(htmlspecialchars($mensagem)) . "</p>";

        $mail->send();
        
        http_response_code(200);
        echo "Sucesso";
        exit;

    } catch (Exception $e) {
        error_log("Erro no PHPMailer: {$mail->ErrorInfo}");
        http_response_code(500);
        echo "Não foi possível enviar a mensagem no momento. Tente novamente mais tarde.";
    }
} else {
    header("Location: index.php");
    exit;
}
?>