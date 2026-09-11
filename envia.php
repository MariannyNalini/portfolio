<?php

session_start();

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
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => "Erro crítico: O arquivo $file não foi encontrado."]);
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
    header('Content-Type: application/json; charset=utf-8');

    // 1. Honeypot check (silencioso para bots)
    if (!empty($_POST['website_trap'])) {
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit;
    }

    // 2. Validação Token CSRF
    if (empty($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Falha na validação de segurança (CSRF invalid). Recarregue a página e tente novamente.']);
        exit;
    }

    // 3. Rate Limiting por Sessão (trava requisições em menos de 30 segundos)
    $now = time();
    if (isset($_SESSION['last_submit']) && ($now - $_SESSION['last_submit']) < 30) {
        http_response_code(429);
        echo json_encode(['error' => 'Muitas tentativas em pouco tempo. Aguarde alguns segundos antes de enviar novamente.']);
        exit;
    }

    // 4. Sanitização e limites de tamanho (truncagem)
    $nome = mb_substr(trim(strip_tags($_POST["nome"] ?? '')), 0, 100);
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_VALIDATE_EMAIL);
    $mensagem = mb_substr(trim(strip_tags($_POST["mensagem"] ?? '')), 0, 2000);

    // 5. Whitelist de valores aceitos para os selects
    $allowed_tipos = ['wordpress', 'landing_page', 'email_html', 'performance'];
    $allowed_prazos = ['urgente', 'curto_prazo', 'medio_prazo'];
    $allowed_layouts = ['aprovado', 'em_desenvolvimento', 'nao', 'implementacao'];
    $allowed_contratos = ['fechado', 'pontual', 'recorrente', 'equipe', 'white_label', 'oportunidade'];

    $raw_tipo = trim($_POST["tipo_projeto"] ?? '');
    $raw_prazo = trim($_POST["prazo_desejado"] ?? '');
    $raw_layout = trim($_POST["possui_layout"] ?? '');
    $raw_contrato = trim($_POST["tipo_contrato"] ?? '');

    $tipo_projeto = in_array($raw_tipo, $allowed_tipos, true) ? $raw_tipo : null;
    $prazo_desejado = in_array($raw_prazo, $allowed_prazos, true) ? $raw_prazo : null;
    $possui_layout = in_array($raw_layout, $allowed_layouts, true) ? $raw_layout : null;
    $tipo_contrato = in_array($raw_contrato, $allowed_contratos, true) ? $raw_contrato : null;

    // 6. Validação dos campos
    $erros = [];

    if (empty($nome)) { $erros[] = "O campo nome é obrigatório."; }
    if (!$email) { $erros[] = "O e-mail informado é inválido."; }
    if (!$tipo_projeto) { $erros[] = "Selecione um tipo de projeto válido."; }
    if (!$prazo_desejado) { $erros[] = "Selecione o prazo desejado."; }
    if (!$possui_layout) { $erros[] = "Selecione a opção de layout."; }
    if (!$tipo_contrato) { $erros[] = "Selecione o tipo de contrato."; }
    if (empty($mensagem)) { $erros[] = "A mensagem não pode estar vazia."; }

    if (!empty($erros)) {
        http_response_code(400);
        echo json_encode(['error' => implode(' ', $erros)]);
        exit;
    }

    // Marca horário do envio bem-sucedido nas validações
    $_SESSION['last_submit'] = time();

    $smtpUser = $_ENV['SMTP_USER'] ?? getenv('SMTP_USER') ?: '';
    $smtpPass = $_ENV['SMTP_PASS'] ?? getenv('SMTP_PASS') ?: '';
    $mailTo = $_ENV['MAIL_TO'] ?? getenv('MAIL_TO') ?: '';

    if (empty($smtpUser) || empty($smtpPass)) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro de configuração: Credenciais de e-mail ausentes no servidor.']);
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

        $mail->setFrom($smtpUser, 'Site - Contato (' . $nome . ')');
        $mail->addAddress($mailTo ?: $smtpUser, 'Marianny');
        $mail->addReplyTo($email, $nome);

        $mail->isHTML(true);
        $mail->Subject = "Novo Projeto: " . htmlspecialchars($tipo_projeto);
        $mail->Body    = "<h3>Novo contato recebido pelo portfólio</h3>" .
                         "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>" .
                         "<p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>" .
                         "<p><strong>Tipo de Projeto:</strong> " . htmlspecialchars($tipo_projeto) . "</p>" .
                         "<p><strong>Prazo Desejado:</strong> " . htmlspecialchars($prazo_desejado) . "</p>" .
                         "<p><strong>Possui layout?:</strong> " . htmlspecialchars($possui_layout) . "</p>" .
                         "<p><strong>Tipo de Contrato:</strong> " . htmlspecialchars($tipo_contrato) . "</p>" .
                         "<p><strong>Mensagem:</strong><br>" . nl2br(htmlspecialchars($mensagem)) . "</p>";

        $mail->send();
        
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Sucesso']);
        exit;

    } catch (Exception $e) {
        error_log("Erro no PHPMailer: {$mail->ErrorInfo}");
        http_response_code(500);
        echo json_encode(['error' => 'Não foi possível enviar a mensagem no momento. Tente novamente mais tarde.']);
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}