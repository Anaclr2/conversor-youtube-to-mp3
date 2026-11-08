<?php
session_start();
require 'functions.php';

$resultado = null;
$erro = null;

if (!empty($_GET['_db_fresh'])) {
    $_SESSION = [];
    session_regenerate_id(true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url'] ?? '');
    if (empty($url)) {
        $erro = 'Por favor, cole uma URL do YouTube válida.';
    } elseif (!validar_youtube_url($url)) {
        $erro = 'URL inválida. Use um link do YouTube (youtube.com ou youtu.be).';
    } else {
        $video_id = extrair_video_id($url);
        if ($video_id) {
            $mock = obter_dados_mock($video_id);
            $resultado = $mock;
            $resultado['video_id'] = $video_id;
            $resultado['url'] = $url;
            registrar_conversao($resultado);
        } else {
            $erro = 'Não foi possível extrair o ID do vídeo. Verifique o link.';
        }
    }
}

$historico = obter_historico();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor YouTube para MP3</title>
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

    <main class="main-content">

        <section class="hero">
            <div class="hero-badge">🎵 Converter Grátis</div>
            <h1 class="hero-title">Conversor YouTube<br><span class="accent">para MP3</span></h1>
            <p class="hero-subtitle">Cole o link de qualquer vídeo do YouTube e baixe o áudio em alta qualidade, de forma rápida e gratuita.</p>
        </section>

        <div class="card-main">
            <form method="POST" action="index.php" class="converter-form">
                <div class="input-group">
                    <span class="input-icon">🔗</span>
                    <input
                        type="text"
                        name="url"
                        id="url-input"
                        class="url-input"
                        placeholder="Cole aqui o link do YouTube... ex: https://youtube.com/watch?v=..."
                        value="<?= htmlspecialchars($_POST['url'] ?? '') ?>"
                        autocomplete="off"
                    >
                    <?php if (!empty($_POST['url'])): ?>
                        <a href="index.php" class="clear-btn" title="Limpar">✕</a>
                    <?php endif; ?>
                </div>

                <?php if ($erro): ?>
                    <div class="alert alert-error">
                        <span>⚠️</span> <?= htmlspecialchars($erro) ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn-convert">
                    <span class="btn-icon">🎵</span>
                    Converter para MP3
                </button>
            </form>

            <?php if ($resultado): ?>
                <div class="result-area">
                    <div class="result-header">
                        <div class="result-status">
                            <span class="status-dot"></span>
                            Conversão concluída!
                        </div>
                    </div>

                    <div class="result-card">
                        <div class="thumb-wrapper">
                            <img
                                src="https://img.youtube.com/vi/<?= htmlspecialchars($resultado['video_id']) ?>/hqdefault.jpg"
                                alt="Thumbnail"
                                class="thumbnail"
                                onerror="this.src='placeholder.php'"
                            >
                            <div class="thumb-overlay">
                                <span class="play-icon">▶</span>
                            </div>
                            <div class="thumb-badge"><?= htmlspecialchars($resultado['qualidade']) ?></div>
                        </div>

                        <div class="result-info">
                            <div class="result-meta-top">
                                <span class="meta-tag">YouTube</span>
                                <span class="meta-tag green">MP3</span>
                            </div>
                            <h2 class="result-title"><?= htmlspecialchars($resultado['titulo']) ?></h2>
                            <p class="result-channel">📺 <?= htmlspecialchars($resultado['canal']) ?></p>

                            <div class="result-stats">
                                <div class="stat">
                                    <span class="stat-icon">⏱️</span>
                                    <div>
                                        <span class="stat-label">Duração</span>
                                        <span class="stat-value"><?= htmlspecialchars($resultado['duracao']) ?></span>
                                    </div>
                                </div>
                                <div class="stat">
                                    <span class="stat-icon">📁</span>
                                    <div>
                                        <span class="stat-label">Tamanho</span>
                                        <span class="stat-value"><?= htmlspecialchars($resultado['tamanho']) ?></span>
                                    </div>
                                </div>
                                <div class="stat">
                                    <span class="stat-icon">🎚️</span>
                                    <div>
                                        <span class="stat-label">Qualidade</span>
                                        <span class="stat-value"><?= htmlspecialchars($resultado['qualidade']) ?></span>
                                    </div>
                                </div>
                                <div class="stat">
                                    <span class="stat-icon">👁️</span>
                                    <div>
                                        <span class="stat-label">Visualizações</span>
                                        <span class="stat-value"><?= htmlspecialchars($resultado['visualizacoes']) ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="download-actions">
                                <a href="download.php?id=<?= urlencode($resultado['video_id']) ?>" class="btn-download">
                                    <span>⬇️</span> Baixar MP3
                                </a>
                                <a href="index.php" class="btn-secondary">
                                    <span>🔄</span> Converter outro
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!$resultado): ?>
                <div class="demo-hint">
                    <p>💡 <strong>Exemplo:</strong> Cole qualquer link como <code>https://www.youtube.com/watch?v=dQw4w9WgXcQ</code></p>
                </div>
            <?php endif; ?>
        </div>

        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Rápido</h3>
                <p>Conversão em segundos, sem espera.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎵</div>
                <h3>Alta Qualidade</h3>
                <p>MP3 em até 320kbps de qualidade.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Seguro</h3>
                <p>Nenhum dado é armazenado.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💸</div>
                <h3>Gratuito</h3>
                <p>100% grátis, sem cadastro.</p>
            </div>
        </section>

        <?php if (!empty($historico)): ?>
            <section class="historico-section">
                <h2 class="section-title">🕘 Conversões Recentes</h2>
                <div class="historico-grid">
                    <?php foreach (array_slice($historico, 0, 4) as $item): ?>
                        <div class="hist-card">
                            <img
                                src="https://img.youtube.com/vi/<?= htmlspecialchars($item['video_id']) ?>/mqdefault.jpg"
                                alt="thumb"
                                class="hist-thumb"
                            >
                            <div class="hist-info">
                                <p class="hist-title"><?= htmlspecialchars(mb_substr($item['titulo'], 0, 40)) ?>...</p>
                                <span class="hist-dur"><?= htmlspecialchars($item['duracao']) ?></span>
                            </div>
                            <a href="download.php?id=<?= urlencode($item['video_id']) ?>" class="hist-dl">⬇️</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

    </main>

    <footer class="site-footer">
        <p>Desenvolvido por: <strong>Ana</strong> &mdash; &copy; <?= date('Y') ?> YTMP3 Converter</p>
        <p class="footer-disclaimer">Este serviço é apenas para fins educacionais e demonstração.</p>
    </footer>

</div>
</body>
</html>
