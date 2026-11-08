<?php

function ler_json($arquivo) {
    if (!file_exists($arquivo)) return [];
    $handle = fopen($arquivo, 'r');
    if (!$handle) return [];
    flock($handle, LOCK_SH);
    $conteudo = '';
    while (!feof($handle)) {
        $conteudo .= fread($handle, 8192);
    }
    flock($handle, LOCK_UN);
    fclose($handle);
    $dados = json_decode($conteudo, true);
    return is_array($dados) ? $dados : [];
}

function salvar_json($arquivo, $dados) {
    $handle = fopen($arquivo, 'c');
    if (!$handle) return false;
    flock($handle, LOCK_EX);
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    flock($handle, LOCK_UN);
    fclose($handle);
    return true;
}

function gerar_id() {
    return uniqid('', true);
}

function formatar_moeda($valor) {
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}

function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function verificar_sessao() {
    if (empty($_SESSION['logado'])) {
        header('Location: login.php');
        exit;
    }
}

function validar_youtube_url($url) {
    return preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|shorts\/|embed\/)|youtu\.be\/).+/i', $url);
}

function extrair_video_id($url) {
    $patterns = [
        '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/'
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $m)) {
            return $m[1];
        }
    }
    // tenta pegar ?v= direto
    parse_str(parse_url($url, PHP_URL_QUERY), $params);
    if (!empty($params['v']) && strlen($params['v']) === 11) {
        return $params['v'];
    }
    return null;
}

function obter_dados_mock($video_id) {
    // Banco de mocks variados por ID
    $mocks = [
        'dQw4w9WgXcQ' => [
            'titulo'        => 'Rick Astley - Never Gonna Give You Up (Official Music Video)',
            'canal'         => 'Rick Astley',
            'duracao'       => '3:33',
            'tamanho'       => '8.2 MB',
            'qualidade'     => '320kbps',
            'visualizacoes' => '1.4 Bilhões',
        ],
        'kJQP7kiw5Fk' => [
            'titulo'        => 'Luis Fonsi - Despacito ft. Daddy Yankee',
            'canal'         => 'Luis Fonsi',
            'duracao'       => '4:42',
            'tamanho'       => '10.8 MB',
            'qualidade'     => '320kbps',
            'visualizacoes' => '8.3 Bilhões',
        ],
        'JGwWNGJdvx8' => [
            'titulo'        => 'Ed Sheeran - Shape of You (Official Music Video)',
            'canal'         => 'Ed Sheeran',
            'duracao'       => '4:23',
            'tamanho'       => '10.1 MB',
            'qualidade'     => '320kbps',
            'visualizacoes' => '6.0 Bilhões',
        ],
    ];
    if (isset($mocks[$video_id])) {
        return $mocks[$video_id];
    }
    // Mock genérico baseado no ID
    $titulos = [
        'Música Incrível para Relaxar - Hits 2024',
        'Lo-fi Hip Hop Mix - Beats para Estudar',
        'Top Hits do Momento - Playlist Completa',
        'Podcast Inspirador - Episódio Especial',
        'Tutorial Completo - Aprenda do Zero',
        'Mix Anos 2000 - Melhores Sucessos',
        'Relaxing Jazz Music - Smooth Collection',
        'Workout Motivation Playlist - Energy Mix',
    ];
    $canais = [
        'Music Channel Brasil', 'Lofi Beats', 'Top Songs', 'Podcast Hub',
        'Tutorial Master', 'Retro Music', 'Jazz Lounge', 'Fitness Beats'
    ];
    $duracoes = ['2:45', '3:12', '4:08', '5:30', '6:22', '3:55', '4:44', '7:01', '2:58', '5:17'];
    $tamanhos = ['6.3 MB', '7.2 MB', '9.4 MB', '12.6 MB', '14.4 MB', '9.1 MB', '10.3 MB', '16.1 MB'];
    $views    = ['1.2M', '500K', '3.4M', '8.7M', '250K', '15M', '900K', '2.1M', '45K', '7.6M'];
    $idx = hexdec(substr(md5($video_id), 0, 4)) % count($titulos);
    return [
        'titulo'        => $titulos[$idx],
        'canal'         => $canais[$idx],
        'duracao'       => $duracoes[$idx % count($duracoes)],
        'tamanho'       => $tamanhos[$idx % count($tamanhos)],
        'qualidade'     => '192kbps',
        'visualizacoes' => $views[$idx % count($views)],
    ];
}

function calcular_tamanho_mp3($duracao, $qualidade) {
    $partes = explode(':', $duracao);
    if (count($partes) === 2) {
        $segundos = ((int)$partes[0]) * 60 + (int)$partes[1];
    } elseif (count($partes) === 3) {
        $segundos = ((int)$partes[0]) * 3600 + ((int)$partes[1]) * 60 + (int)$partes[2];
    } else {
        $segundos = 180;
    }

    $kbps = (int) filter_var($qualidade, FILTER_SANITIZE_NUMBER_INT);
    if ($kbps <= 0) $kbps = 192;

    $bytes = ($kbps * 1000 * $segundos) / 8;
    $mb = $bytes / (1024 * 1024);

    if ($mb < 0.1) $mb = 0.1;

    return number_format($mb, 1, '.', '') . ' MB';
}

function registrar_conversao($dados) {
    $db = ler_json('db.json');
    if (!isset($db['conversoes'])) $db['conversoes'] = [];
    // evita duplicatas recentes (atualiza se já existir com nova qualidade)
    foreach ($db['conversoes'] as $idx => $c) {
        if ($c['video_id'] === $dados['video_id']) {
            array_splice($db['conversoes'], $idx, 1);
            break;
        }
    }
    array_unshift($db['conversoes'], [
        'id'            => gerar_id(),
        'video_id'      => $dados['video_id'],
        'titulo'        => $dados['titulo'],
        'canal'         => $dados['canal'],
        'duracao'       => $dados['duracao'],
        'tamanho'       => $dados['tamanho'],
        'qualidade'     => $dados['qualidade'],
        'visualizacoes' => $dados['visualizacoes'],
        'criado_em'     => date('Y-m-d H:i:s'),
    ]);
    // mantém apenas 20 últimos
    $db['conversoes'] = array_slice($db['conversoes'], 0, 20);
    salvar_json('db.json', $db);
}

function obter_historico() {
    $db = ler_json('db.json');
    return $db['conversoes'] ?? [];
}