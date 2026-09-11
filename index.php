<?php
$lang = ($_GET['lang'] ?? 'pt') === 'en' ? 'en' : 'pt';
$t = include "lang/{$lang}.php";
?>
<!DOCTYPE html>
<html lang="<?php echo $lang === 'en' ? 'en' : 'pt-BR'; ?>">
    <head>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-WJ5PXV8HSQ"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-WJ5PXV8HSQ');
        </script>
        <meta charset="UTF-8">
        <title><?php echo $t['meta_title']; ?></title>
        <meta name="description" content="<?php echo $t['meta_description']; ?>">
        <meta name="keywords" content="<?php echo $t['meta_keywords']; ?>">
        <meta name="author" content="Marianny Nalini">

        <link rel="canonical" href="https://mariannynalini.com.br/<?php echo $lang === 'en' ? '?lang=en' : ''; ?>">
        <link rel="alternate" hreflang="pt-BR" href="https://mariannynalini.com.br/">
        <link rel="alternate" hreflang="en" href="https://mariannynalini.com.br/?lang=en">
        <link rel="alternate" hreflang="x-default" href="https://mariannynalini.com.br/">

        <meta property="og:title" content="<?php echo $t['meta_title']; ?>">
        <meta property="og:description" content="<?php echo $t['meta_description']; ?>">
        <meta property="og:image" content="https://mariannynalini.com.br/assets/img/hero.png">
        <meta property="og:url" content="https://mariannynalini.com.br/<?php echo $lang === 'en' ? '?lang=en' : ''; ?>">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="<?php echo $lang === 'en' ? 'en_US' : 'pt_BR'; ?>">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo $t['meta_title']; ?>">
        <meta name="twitter:description" content="<?php echo $t['meta_description']; ?>">
        <meta name="twitter:image" content="https://mariannynalini.com.br/assets/img/hero.png">

        <meta name="robots" content="index, follow">
        <meta name="googlebot" content="index, follow">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="assets/css/style.css?v=2">
        <link rel="icon" type="image/png" href="assets/img/favicon/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="assets/img/favicon/favicon.svg" />
        <link rel="shortcut icon" href="assets/img/favicon/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="Marianny Nalini" />
        <link rel="manifest" href="assets/img/favicon/site.webmanifest" />
    </head>
    <body data-lang="<?php echo $lang; ?>">

        <header>
            <div class="container">
                <div class="logo">
                    <span class="name">Marianny</span>
                    <span class="role"><?php echo $t['role']; ?></span>
                </div>
                <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <nav>
                    <button class="menu-toggle" aria-expanded="false" aria-controls="primary-menu" aria-label="Abrir menu">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>

                    <div class="nav-content" id="primary-menu">
                        <ul>
                            <li><a href="#inicio"><?php echo $t['nav_inicio']; ?></a></li>
                            <li><a href="#servicos"><?php echo $t['nav_servicos']; ?></a></li>
                            <li><a href="#projetos"><?php echo $t['nav_projetos']; ?></a></li>
                            <li><a href="#processo"><?php echo $t['nav_processo']; ?></a></li>
                        </ul>
                        <div class="nav-btn-container">
                            <a href="#contato" class="btn btn-primary"><?php echo $t['btn_orcamento']; ?></a>
                        </div>
                        <div class="lang-switcher">
                            <a href="?lang=pt" class="<?php echo $lang === 'pt' ? 'active' : ''; ?>">PT</a>
                            <span class="lang-divider">/</span>
                            <a href="?lang=en" class="<?php echo $lang === 'en' ? 'active' : ''; ?>">EN</a>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
        <div class="menu-overlay"></div>

        <main>
            <section id="inicio">
                <div class="container">
                    <div class="hero-image-wrapper">
                        <img src="assets/img/hero.png" alt="Mockup de dispositivos exibindo projetos digitais">
                    </div>
                    <div>
                        <div class="content">
                            <div>
                                <h1>
                                    <?php echo $t['hero_title']; ?>
                                </h1>
                            </div>
                            <div>
                                <p>
                                    <?php echo $t['hero_desc']; ?>
                                </p>
                            </div>
                            <div>
                                <ul>
                                    <li>
                                        <i class="fa-brands fa-wordpress"></i> 
                                        <span><?php echo $t['hero_item_1']; ?></span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-code"></i> 
                                        <span><?php echo $t['hero_item_2']; ?></span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-envelope"></i> 
                                        <span><?php echo $t['hero_item_3']; ?></span>
                                    </li>
                                    <li>
                                        <i class="fa-solid fa-gauge-high"></i> 
                                        <span><?php echo $t['hero_item_4']; ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div>
                                <div class="btns">
                                    <a href="#contato" class="btn btn-primary"><?php echo $t['btn_conversar']; ?></a>
                                    <a href="#projetos" class="btn btn-secondary"><?php echo $t['btn_ver_projetos']; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="servicos">
                <div class="container">
                    <h3 class="subheading"><?php echo $t['servicos_sub']; ?></h3>
                    <h2 class="heading"><?php echo $t['servicos_head']; ?></h2>
                    <div class="grid">
                        <article>
                            <div class="icon">
                                <i class="fa-brands fa-wordpress"></i>
                            </div>
                            <div>
                                <h3><?php echo $t['srv_1_title']; ?></h3>
                                <p><?php echo $t['srv_1_desc']; ?></p>
                            </div>
                            <a href="#contato"><?php echo $t['btn_saber_mais']; ?></a>
                        </article>
                        <article>
                            <div class="icon">
                                <i class="fa-solid fa-code"></i>
                            </div>
                            <div>
                                <h3><?php echo $t['srv_2_title']; ?></h3>
                                <p><?php echo $t['srv_2_desc']; ?></p>
                            </div>
                            <a href="#contato"><?php echo $t['btn_saber_mais']; ?></a>
                        </article>
                        <article>
                            <div class="icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div>
                                <h3><?php echo $t['srv_3_title']; ?></h3>
                                <p><?php echo $t['srv_3_desc']; ?></p>
                            </div>
                            <a href="#contato"><?php echo $t['btn_saber_mais']; ?></a>
                        </article>
                        <article>
                            <div class="icon">
                                <i class="fa-solid fa-gauge-high"></i>
                            </div>
                            <div>
                                <h3><?php echo $t['srv_4_title']; ?></h3>
                                <p><?php echo $t['srv_4_desc']; ?></p>
                            </div>
                            <a href="#contato"><?php echo $t['btn_saber_mais']; ?></a>
                        </article>
                    </div>
                </div>
            </section>

            <section id="para-quem">
                <div class="container">
                    <h3 class="subheading"><?php echo $t['para_quem_sub']; ?></h3>
                    <h2 class="heading"><?php echo $t['para_quem_head']; ?></h2>
                    <div class="grid">
                        <article>
                            <div>
                                <div class="icon">
                                    <i class="fa-solid fa-rocket"></i>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo $t['pq_1_title']; ?></h3>
                                <p><?php echo $t['pq_1_desc']; ?></p>
                            </div>
                        </article>
                        <article>
                            <div>
                                <div class="icon">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo $t['pq_2_title']; ?></h3>
                                <p><?php echo $t['pq_2_desc']; ?></p>
                            </div>
                        </article>
                        <article>
                            <div>
                                <div class="icon">
                                    <i class="fa-regular fa-building"></i>
                                </div>
                            </div>
                            <div>
                                <h3><?php echo $t['pq_3_title']; ?></h3>
                                <p><?php echo $t['pq_3_desc']; ?></p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="projetos" class="projetos">
                <div class="container">
                    <h3 class="subheading"><?php echo $t['projetos_sub']; ?></h3>
                    <h2 class="heading"><?php echo $t['projetos_head']; ?></h2>
                    <div class="grid" id="projetos-grid">
                    </div>
                    <div class="btn-container">
                        <a href="#" id="load-more-btn" class="btn btn-primary"><?php echo $t['btn_load_more']; ?></a>
                    </div>
                </div>
            </section>

            <section id="processo">
                <div class="container"> 
                    <h3 class="subheading"><?php echo $t['como_trabalho_sub']; ?></h3>
                    <h2 class="heading"><?php echo $t['como_trabalho_head']; ?></h2>
                    <div class="grid">
                        <article>
                            <div class="icon">
                                <i class="fa-solid fa-bullseye" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo $t['passo_1_titulo']; ?></h3>
                            <p><?php echo $t['passo_1_desc']; ?></p>
                        </article>
        
                        <article>
                            <div class="icon">
                                <i class="fa-solid fa-code" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo $t['passo_2_titulo']; ?></h3>
                            <p><?php echo $t['passo_2_desc']; ?></p>
                        </article>

                        <article>
                            <div class="icon">
                                <i class="fa-solid fa-clipboard-check" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo $t['passo_3_titulo']; ?></h3>
                            <p><?php echo $t['passo_3_desc']; ?></p>
                        </article>
        
                        <article>
                            <div class="icon">
                                <i class="fa-solid fa-rocket" aria-hidden="true"></i>
                            </div>
                            <h3><?php echo $t['passo_4_titulo']; ?></h3>
                            <p><?php echo $t['passo_4_desc']; ?></p>
                        </article>
                    </div>
                </div>
            </section>

            <section id="equipe">
                <div class="container">
                    <div>
                        <h2 class="heading"><?php echo $t['agencias_equipe_head']; ?></h2>
                    </div>
                    <div>
                        <?php echo $t['agencias_equipe_desc']; ?>
                        <ul class="grid">
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-user-clock"></i>
                                </div> 
                                <div>
                                    <span>
                                        <?php echo $t['agencia_item_1']; ?>
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <div>
                                    <span>
                                        <?php echo $t['agencia_item_2']; ?>
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="fa-solid fa-user-secret"></i>
                                </div>
                                <div>
                                    <span>
                                        <?php echo $t['agencia_item_3']; ?>
                                    </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <a href="#contato" class="btn btn-primary"><?php echo $t['btn_agencia_conversar']; ?></a>
                    </div>
                </div>
            </section>
            <section id="agencias">
                <div class="container"> 
                    <div>
                        <h3 class="subheading"><?php echo $t['ajuda_sub']; ?></h3>
                        <h2 class="heading"><?php echo $t['ajuda_head']; ?></h2>
                    </div>
                    <div>
                        <ul class="grid">
                            <li>
                                <div>
                                    <i class="fa-solid fa-gauge"></i>
                                </div> 
                                <div>
                                    <span><?php echo $t['ajuda_1']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-mobile-screen-button"></i> 
                                </div>
                                <div>
                                    <span><?php echo $t['ajuda_2']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-users"></i>
                                </div> 
                                <div>
                                    <span><?php echo $t['ajuda_3']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-rocket"></i>
                                </div> 
                                <div>
                                    <span><?php echo $t['ajuda_4']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-chart-line"></i>
                                </div> 
                                <div>
                                    <span><?php echo $t['ajuda_5']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-bug"></i>
                                </div>
                                <div>
                                    <span><?php echo $t['ajuda_6']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-code"></i>
                                </div> 
                                <div>
                                    <span><?php echo $t['ajuda_7']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-gears"></i>
                                </div>
                                <div>
                                    <span><?php echo $t['ajuda_8']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                </div>
                                <div>
                                    <span><?php echo $t['ajuda_9']; ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            <section id="contato">
                <div class="container">
                    <div class="content">
                        <div>
                            <h2 class="heading"><?php echo $t['contato_head']; ?></h2>
                            <p><?php echo $t['contato_desc']; ?></p>
                        </div>
                        <ul>
                            <li>
                                <div>
                                    <i class="fa-solid fa-bolt"></i>
                                </div>
                                <div>
                                    <span><?php echo $t['contato_beneficio_1']; ?></span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <i class="fa-solid fa-stopwatch"></i>
                                </div>
                                <div>
                                    <span><?php echo $t['contato_beneficio_2']; ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <form action="envia.php" method="POST" novalidate>
                            <div>
                                <label for="nome"><?php echo $t['form_nome']; ?></label>
                                <input type="text" id="nome" name="nome" placeholder="<?php echo $t['form_nome_ph']; ?>" required>
                            </div>
                            <div>
                                <label for="email"><?php echo $t['form_email']; ?></label>
                                <input type="email" id="email" name="email" placeholder="<?php echo $t['form_email_ph']; ?>" required>
                            </div>
                            
                            <div class="custom-select-wrapper" data-name="tipo_projeto">
                                <label><?php echo $t['form_tipo']; ?></label>
                                <div class="custom-select">
                                    <div class="select-trigger" tabindex="0">
                                        <span><?php echo $t['form_selecione']; ?></span>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                    <div class="custom-options">
                                        <div class="custom-option" data-value="wordpress"><?php echo $t['opt_wordpress']; ?></div>
                                        <div class="custom-option" data-value="landing_page"><?php echo $t['opt_landing_page']; ?></div>
                                        <div class="custom-option" data-value="email_html"><?php echo $t['opt_email_html']; ?></div>
                                        <div class="custom-option" data-value="performance"><?php echo $t['opt_performance']; ?></div>
                                    </div>
                                </div>
                                <input type="hidden" name="tipo_projeto" id="tipo_projeto" required>
                            </div>

                            <div class="custom-select-wrapper" data-name="prazo_desejado">
                                <label><?php echo $t['form_prazo']; ?></label>
                                <div class="custom-select">
                                    <div class="select-trigger" tabindex="0">
                                        <span><?php echo $t['form_selecione']; ?></span>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                    <div class="custom-options">
                                        <div class="custom-option" data-value="urgente"><?php echo $t['opt_urgente']; ?></div>
                                        <div class="custom-option" data-value="curto_prazo"><?php echo $t['opt_curto']; ?></div>
                                        <div class="custom-option" data-value="medio_prazo"><?php echo $t['opt_medio']; ?></div>
                                    </div>
                                </div>
                                <input type="hidden" name="prazo_desejado" id="prazo_desejado" required>
                            </div>

                            <div class="custom-select-wrapper" data-name="possui_layout">
                                <label><?php echo $t['form_layout']; ?></label>
                                <div class="custom-select">
                                    <div class="select-trigger" tabindex="0">
                                        <span><?php echo $t['form_selecione']; ?></span>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                    <div class="custom-options">
                                        <div class="custom-option" data-value="aprovado"><?php echo $t['opt_layout_aprovado']; ?></div>
                                        <div class="custom-option" data-value="em_desenvolvimento"><?php echo $t['opt_layout_desenvolvimento']; ?></div>
                                        <div class="custom-option" data-value="nao"><?php echo $t['opt_layout_nao']; ?></div>
                                        <div class="custom-option" data-value="implementacao"><?php echo $t['opt_layout_implementacao']; ?></div>
                                    </div>
                                </div>
                                <input type="hidden" name="possui_layout" id="possui_layout" required>
                            </div>

                            <div class="custom-select-wrapper" data-name="tipo_contrato">
                                <label><?php echo $t['form_contrato']; ?></label>
                                <div class="custom-select">
                                    <div class="select-trigger" tabindex="0">
                                        <span><?php echo $t['form_selecione']; ?></span>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>
                                    <div class="custom-options">
                                        <div class="custom-option" data-value="fechado"><?php echo $t['opt_contrato_fechado']; ?></div>
                                        <div class="custom-option" data-value="pontual"><?php echo $t['opt_contrato_pontual']; ?></div>
                                        <div class="custom-option" data-value="recorrente"><?php echo $t['opt_contrato_recorrente']; ?></div>
                                        <div class="custom-option" data-value="equipe"><?php echo $t['opt_contrato_equipe']; ?></div>
                                        <div class="custom-option" data-value="white_label"><?php echo $t['opt_contrato_white']; ?></div>
                                        <div class="custom-option" data-value="oportunidade"><?php echo $t['opt_contrato_oportunidade']; ?></div>
                                    </div>
                                </div>
                                <input type="hidden" name="tipo_contrato" id="tipo_contrato" required>
                            </div>

                            <div>
                                <label for="mensagem"><?php echo $t['form_mensagem']; ?></label>
                                <textarea id="mensagem" name="mensagem" placeholder="<?php echo $t['form_mensagem_ph']; ?>"></textarea>
                            </div>

                            <div style="display:none;">
                                <label>Não preencha este campo se for humano:</label>
                                <input type="text" name="website_trap" value="">
                            </div>
                            <button type="submit" class="btn btn-primary"><?php echo $t['form_submit']; ?></button>
                        </form>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <div class="container"> 
                <div class="footer-col">
                    <div class="logo">
                        <span class="name">Marianny</span>
                        <span class="role"><?php echo $t['role']; ?></span>
                    </div>
                    <p><?php echo $t['footer_desc']; ?></p>
                </div>
                <div class="footer-col">
                    <h4><?php echo $t['footer_navegacao']; ?></h4>
                    <ul>
                        <li><a href="#inicio"><?php echo $t['nav_inicio']; ?></a></li>
                        <li><a href="#servicos"><?php echo $t['nav_servicos']; ?></a></li>
                        <li><a href="#projetos"><?php echo $t['nav_projetos']; ?></a></li>
                        <li><a href="#processo"><?php echo $t['nav_processo']; ?></a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><?php echo $t['footer_servicos']; ?></h4>
                    <ul>
                        <li><?php echo $t['srv_1_title']; ?></li>
                        <li><?php echo $t['srv_2_title']; ?></li>
                        <li><?php echo $t['srv_3_title']; ?></li>
                        <li><?php echo $t['srv_4_title']; ?></li>
                        <li><?php echo $t['footer_servico_5']; ?></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><?php echo $t['footer_contato_titulo']; ?></h4>
                    <ul>
                        <li><i class="fa-solid fa-envelope"></i> marianny.nalini@gmail.com</li>
                        <li><a href="https://www.linkedin.com/in/marianny-nalini/" target="_blank"><i class="fa-brands fa-linkedin"></i> LinkedIn</a></li>
                        <li><i class="fa-solid fa-location-dot"></i> São Vicente, SP</li>
                    </ul>
                </div>
            </div>
        </footer>
        <div id="copyright">
            <div class="container">
                <p>&copy; <?php echo $t['copyright']; ?></p>
            </div>
        </div>
        <div id="modal-sucesso" class="modal">
            <div class="modal-content">
                <h3><?php echo $t['modal_titulo']; ?></h3>
                <p><?php echo $t['modal_desc']; ?></p>
                <button id="fechar-modal" class="btn btn-primary"><?php echo $t['modal_fechar']; ?></button>
            </div>
        </div>
        <script src="assets/js/main.js?v=2"></script>
    </body>
</html>