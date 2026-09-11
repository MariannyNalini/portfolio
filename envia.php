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

    if (!empty($_POST['website_trap'])) {
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit;
    }

    if (empty($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        echo json_encode(['error' => 'Falha na validação de segurança. Recarregue a página e tente novamente.']);
        exit;
    }

    $now = time();
    if (isset($_SESSION['last_submit']) && ($now - $_SESSION['last_submit']) < 30) {
        http_response_code(429);
        echo json_encode(['error' => 'Muitas tentativas em pouco tempo. Aguarde alguns segundos antes de enviar novamente.']);
        exit;
    }

    $nome = mb_substr(trim(strip_tags($_POST["nome"] ?? '')), 0, 100);
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_VALIDATE_EMAIL);
    $mensagem = mb_substr(trim(strip_tags($_POST["mensagem"] ?? '')), 0, 2000);

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

    $smtpUser = $_ENV['SMTP_USER'] ?? getenv('SMTP_USER') ?: '';
    $smtpPass = $_ENV['SMTP_PASS'] ?? getenv('SMTP_PASS') ?: '';
    $mailTo = $_ENV['MAIL_TO'] ?? getenv('MAIL_TO') ?: '';

    if (empty($smtpUser) || empty($smtpPass)) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro de configuração: Credenciais de e-mail ausentes no servidor.']);
        exit;
    }

    $mapa_tipos = [
        'wordpress' => 'WordPress',
        'landing_page' => 'Landing Page',
        'email_html' => 'Email HTML',
        'performance' => 'Performance'
    ];

    $mapa_prazos = [
        'urgente' => 'Urgente',
        'curto_prazo' => 'Até 2 semanas',
        'medio_prazo' => '1 mês ou mais'
    ];

    $mapa_layouts = [
        'aprovado' => 'Sim, está aprovado',
        'em_desenvolvimento' => 'Sim, mas ainda está em desenvolvimento',
        'nao' => 'Não',
        'implementacao' => 'Preciso apenas da implementação técnica'
    ];

    $mapa_contratos = [
        'fechado' => 'Projeto fechado',
        'pontual' => 'Demanda pontual',
        'recorrente' => 'Suporte recorrente',
        'equipe' => 'Extensão de equipe',
        'white_label' => 'White-label',
        'oportunidade' => 'Oportunidade profissional'
    ];

    $label_tipo = $mapa_tipos[$tipo_projeto] ?? $tipo_projeto;
    $label_prazo = $mapa_prazos[$prazo_desejado] ?? $prazo_desejado;
    $label_layout = $mapa_layouts[$possui_layout] ?? $possui_layout;
    $label_contrato = $mapa_contratos[$tipo_contrato] ?? $tipo_contrato;

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
        $mail->Subject = "Novo Projeto: " . htmlspecialchars($label_tipo);
        $mail->Body    = "<h3>Novo contato recebido pelo portfólio</h3>" .
                         "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>" .
                         "<p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>" .
                         "<p><strong>Tipo de Projeto:</strong> " . htmlspecialchars($label_tipo) . "</p>" .
                         "<p><strong>Prazo Desejado:</strong> " . htmlspecialchars($label_prazo) . "</p>" .
                         "<p><strong>Possui layout?:</strong> " . htmlspecialchars($label_layout) . "</p>" .
                         "<p><strong>Tipo de Contrato:</strong> " . htmlspecialchars($label_contrato) . "</p>" .
                         "<p><strong>Mensagem:</strong><br>" . nl2br(htmlspecialchars($mensagem)) . "</p>";

        $mail->send();

        $_SESSION['last_submit'] = time();
        
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