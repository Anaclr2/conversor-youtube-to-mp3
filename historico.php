<?php
session_start();
require 'functions.php';

if (!empty($_GET['_db_fresh'])) { $_SESSION = []; session_regenerate_id(true); }

if (isset($_GET['limpar'])) {
    $db = ler_json('db.json');
    $db['conversoes'] = [];
    salvar_json('db.json', $db);
    header('Location: historico.php');
    exit;
}

$historico = obter_historico();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico — YTMP3</title>
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
            <a href="sobre.php" class="nav-link">Sobre</a>
        </nav>
    </header>

    <main class="page-inner">
        <a href="index.php" class="btn-back">← Voltar</a>

        <h1 class="page-title">🕘 Histórico de Conversões</h1>
        <p class="page-subtitle">Todos os vídeos convertidos recentemente neste dispositivo.</p>

        <?php if (empty($historico)): ?>
            <div class="card">
                <div class="empty-state">
                    <span class="empty-icon">📭</span>
                    <h3>Nenhuma conversão ainda</h3>
                    <p>Converta um vídeo do YouTube para começar seu histórico.</p>
                </div>
            </div>
        <?php else: ?>
            <div style="display:flex;justify-content:flex-end;margin-bottom:16px;">
                <a href="historico.php?limpar=1" class="btn-back" style="color:#fc8181;border-color:rgba(229,62,62,.3);background:rgba(229,62,62,.08);" onclick="return confirm('Limpar todo o histórico?')">
                    🗑️ Limpar histórico
                </a>
            </div>

            <?php foreach ($historico as $item): ?>
                <div class="card" style="display:flex;gap:18px;align-items:center;padding:16px 20px;">
                    <img
                        src="https://img.youtube.com/vi/<?= htmlspecialchars($item['video_id']) ?>/mqdefault.jpg"
                        alt="thumb"
                        style="width:110px;height:70px;object-fit:cover;border-radius:8px;flex-shrink:0;background:var(--bg3);"
                    >
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:.95rem;font-weight:700;color:var(--text);margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?= htmlspecialchars($item['titulo']) ?>
                        </p>
                        <p style="font-size:.82rem;color:var(--text2);margin-bottom:8px;">📺 <?= htmlspecialchars($item['canal']) ?></p>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            <span style="font-size:.77rem;color:var(--text3);">⏱️ <?= htmlspecialchars($item['duracao']) ?></span>
                            <span style="font-size:.77rem;color:var(--text3);">📁 <?= htmlspecialchars($item['tamanho']) ?></span>
                            <span style="font-size:.77rem;color:var(--text3);">🎚️ <?= htmlspecialchars($item['qualidade']) ?></span>
                            <?php if (!empty($item['criado_em'])): ?>
                                <span style="font-size:.77rem;color:var(--text3);">📅 <?= htmlspecialchars($item['criado_em']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="flex-shrink:0;display:flex;flex-direction:column;gap:8px;">
                        <a href="download.php?id=<?= urlencode($item['video_id']) ?>" class="btn-download" style="font-size:.82rem;padding:9px 14px;">
                            ⬇️ Baixar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <p>Desenvolvido por: <strong>Ana</strong> &mdash; &copy; <?= date('Y') ?> YTMP3 Converter</p>
        <p class="footer-disclaimer">Este serviço é apenas para fins educacionais e demonstração.</p>
    </footer>
</div>
</body>
</html>
