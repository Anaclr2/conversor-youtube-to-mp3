<?php
session_start();
require 'functions.php';

if (!empty($_GET['_db_fresh'])) { $_SESSION = []; session_regenerate_id(true); }

$video_id = trim($_GET['id'] ?? '');

if (empty($video_id)) {
    header('Location: index.php');
    exit;
}

$historico = obter_historico();
$dados = null;
foreach ($historico as $item) {
    if ($item['video_id'] === $video_id) {
        $dados = $item;
        break;
    }
}

if (!$dados) {
    // recria mock para IDs não salvos
    $dados = obter_dados_mock($video_id);
    $dados['video_id'] = $video_id;
    $dados['duracao']  = $dados['duracao']  ?? '3:30';
    $dados['tamanho']  = $dados['tamanho']  ?? '8.0 MB';
    $dados['qualidade'] = $dados['qualidade'] ?? '192kbps';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baixar MP3 — YTMP3</title>
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
            <a href="historico.php" class="nav-link">Histórico</a>
            <a href="sobre.php" class="nav-link">Sobre</a>
        </nav>
    </header>

    <main class="page-inner">
        <a href="index.php" class="btn-back">← Voltar ao início</a>

        <div class="card download-card">
            <span class="download-anim">⬇️</span>
            <h2>Preparando seu download...</h2>
            <p>O arquivo MP3 está sendo gerado. Em breve estará pronto!</p>

            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>

            <div class="download-info-box">
                <div class="di-item">
                    <span class="di-label">Duração</span>
                    <span class="di-value"><?= htmlspecialchars($dados['duracao']) ?></span>
                </div>
                <div class="di-item">
                    <span class="di-label">Tamanho</span>
                    <span class="di-value"><?= htmlspecialchars($dados['tamanho']) ?></span>
                </div>
                <div class="di-item">
                    <span class="di-label">Qualidade</span>
                    <span class="di-value"><?= htmlspecialchars($dados['qualidade']) ?></span>
                </div>
                <div class="di-item">
                    <span class="di-label">Formato</span>
                    <span class="di-value">MP3</span>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <img
                    src="https://img.youtube.com/vi/<?= htmlspecialchars($video_id) ?>/mqdefault.jpg"
                    alt="thumbnail"
                    style="width:220px;border-radius:10px;border:2px solid var(--border);"
                >
            </div>

            <p style="font-size:.85rem;color:var(--text3);margin-bottom:4px;">🎵 <?= htmlspecialchars($dados['titulo'] ?? 'Vídeo do YouTube') ?></p>

            <div style="margin-top:20px;padding:14px;background:rgba(229,62,62,.08);border:1px solid rgba(229,62,62,.2);border-radius:10px;font-size:.83rem;color:var(--text2);max-width:420px;margin-inline:auto;">
                ⚠️ <strong>Simulação:</strong> Em um sistema real, o arquivo MP3 seria gerado via API (youtube-dl, yt-dlp, etc.) e enviado como download. Esta é uma demonstração do fluxo de UI.
            </div>
        </div>

        <div style="text-align:center;margin-top:20px;">
            <a href="index.php" class="btn-back" style="display:inline-flex;">🔄 Converter outro vídeo</a>
        </div>
    </main>

    <footer class="site-footer">
        <p>Desenvolvido por: <strong>Ana</strong> &mdash; &copy; <?= date('Y') ?> YTMP3 Converter</p>
        <p class="footer-disclaimer">Este serviço é apenas para fins educacionais e demonstração.</p>
    </footer>
</div>
</body>
</html>
