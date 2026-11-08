<?php
session_start();
require 'functions.php';

if (!empty($_GET['_db_fresh'])) { $_SESSION = []; session_regenerate_id(true); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre — YTMP3</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="bg-gradient"></div>
<div class="page-wrapper">

    <header class="site-header">
        <div class="logo">
            <span class="logo-icon">▶</span>
            <span class="logo-text">YT<span class="logo-accent">MP3</span></span>
        </div>
        <nav class="header-nav">
            <a href="index.php" class="nav-link">Início</a>
            <a href="historico.php" class="nav-link">Histórico</a>
        </nav>
    </header>

    <main class="page-inner">
        <a href="index.php" class="btn-back">← Voltar</a>

        <h1 class="page-title">ℹ️ Sobre o YTMP3</h1>
        <p class="page-subtitle">Saiba mais sobre esta ferramenta de conversão.</p>

        <div class="card" style="margin-bottom:16px;">
            <h2 style="font-size:1.1rem;font-weight:700;color:var(--text);margin-bottom:12px;">🎵 O que é o YTMP3?</h2>
            <p style="color:var(--text2);font-size:.93rem;line-height:1.7;">
                O <strong style="color:var(--text);">YTMP3 Converter</strong> é uma aplicação web que permite converter vídeos do YouTube
                em arquivos de áudio MP3 de forma rápida, gratuita e sem necessidade de cadastro.
                Basta colar o link do vídeo e clicar em Converter!
            </p>
        </div>

        <div class="card" style="margin-bottom:16px;">
            <h2 style="font-size:1.05rem;font-weight:700;color:var(--text);margin-bottom:16px;">✨ Recursos</h2>
            <div class="sobre-grid">
                <div class="sobre-item">
                    <h3>⚡ Velocidade</h3>
                    <p>Conversão realizada em segundos, sem filas ou esperas longas.</p>
                </div>
                <div class="sobre-item">
                    <h3>🎵 Qualidade</h3>
                    <p>Suporte a múltiplas qualidades: 128kbps, 192kbps e 320kbps.</p>
                </div>
                <div class="sobre-item">
                    <h3>📱 Responsivo</h3>
                    <p>Funciona perfeitamente em desktop, tablet e celular.</p>
                </div>
                <div class="sobre-item">
                    <h3>🔒 Privacidade</h3>
                    <p>Nenhuma conta necessária. Seus dados não são armazenados.</p>
                </div>
                <div class="sobre-item">
                    <h3>🌐 Compatível</h3>
                    <p>Suporta URLs do youtube.com e do encurtador youtu.be.</p>
                </div>
                <div class="sobre-item">
                    <h3>💸 Gratuito</h3>
                    <p>100% gratuito, sem limite de conversões diárias.</p>
                </div>
            </div>
        </div>

        <div class="disclaimer-box">
            <p><strong>⚠️ Aviso Legal:</strong> Esta ferramenta foi desenvolvida <strong>exclusivamente para fins educacionais e demonstração de interface</strong>.
            Respeite os <a href="#" style="color:var(--accent2);">Termos de Serviço do YouTube</a> e as leis de direitos autorais ao baixar conteúdo.
            Baixe apenas conteúdos para os quais você tem permissão. O download de conteúdo protegido por direitos autorais sem autorização pode ser ilegal em sua jurisdição.</p>
        </div>
    </main>

    <footer class="site-footer">
        <p>Desenvolvido por: <strong>Ana</strong> &mdash; &copy; <?= date('Y') ?> YTMP3 Converter</p>
        <p class="footer-disclaimer">Este serviço é apenas para fins educacionais e demonstração.</p>
    </footer>
</div>
</body>
</html>
