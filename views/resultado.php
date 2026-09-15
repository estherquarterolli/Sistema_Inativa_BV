<?php
$percentual = $total > 0 ? (int) round(($encontrados / $total) * 100) : 0;
$quantidadeNaoEncontrados = count($naoEncontrados);
$downloadHref = !empty($downloadData)
    ? $downloadData
    : '/baixar?token=' . rawurlencode($token);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#f4f0e8">
<title>Resultado — Inativa aí</title>
<link rel="stylesheet" href="/assets/app.css">
</head>
<body class="result-page">
<header class="result-header">
    <a class="brand" href="/" aria-label="Inativa aí — início">
        <span class="brand-word">Inativa aí</span>
        <span class="brand-dot" aria-hidden="true"></span>
    </a>
</header>

<main class="result-main">
    <article class="result-card">
        <section class="result-hero">
            <div class="success-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m5 12 4 4L19 6"/>
                </svg>
            </div>

            <p class="card-kicker">Processamento concluído</p>
            <h1>Sua planilha está pronta.</h1>
            <p class="result-summary">
                Encontramos <?= (int) $encontrados ?> de <?= (int) $total ?> nomes únicos nos arquivos enviados.
            </p>

            <div class="result-numbers" aria-label="Resumo do processamento">
                <div class="result-number">
                    <strong><?= (int) $total ?></strong>
                    <span>nomes lidos</span>
                </div>
                <div class="result-number">
                    <strong><?= (int) $encontrados ?></strong>
                    <span>localizados</span>
                </div>
                <div class="result-number">
                    <strong><?= $quantidadeNaoEncontrados ?></strong>
                    <span>não encontrados</span>
                </div>
            </div>

            <div class="match-track" aria-label="<?= $percentual ?>% dos nomes localizados">
                <div class="match-fill" style="width: <?= $percentual ?>%"></div>
            </div>

            <a class="download-button"
               href="<?= htmlspecialchars($downloadHref, ENT_QUOTES, 'UTF-8') ?>"
               download="inativacao.xlsx">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>
                </svg>
                Baixar planilha final
            </a>
        </section>

        <div class="result-details">
            <?php if (!empty($contagensArquivos)): ?>
            <section class="section-box" aria-labelledby="files-title">
                <h2 id="files-title" class="section-heading">
                    <span>Nomes lidos por arquivo</span>
                    <span><?= count($contagensArquivos) ?> arquivo(s)</span>
                </h2>
                <ul class="file-counts">
                    <?php foreach ($contagensArquivos as $item): ?>
                    <li>
                        <span title="<?= htmlspecialchars($item['arquivo'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['arquivo'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="count-pill"><?= (int) $item['quantidade'] ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>

            <?php if (!empty($naoEncontrados)): ?>
            <section class="section-box" aria-labelledby="missing-title">
                <h2 id="missing-title" class="section-heading">O que precisa de atenção</h2>
                <details class="missing-details">
                    <summary><?= $quantidadeNaoEncontrados ?> nome(s) não encontrados na base principal</summary>
                    <ol class="missing-list">
                        <?php foreach ($naoEncontrados as $nome): ?>
                        <li><?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ol>
                    <p class="missing-note">Eles também estão separados em uma aba dentro da planilha baixada.</p>
                </details>
            </section>
            <?php endif; ?>

            <a class="new-batch" href="/">
                <span aria-hidden="true">←</span>
                Processar um novo lote
            </a>
        </div>
    </article>
</main>
</body>
</html>
