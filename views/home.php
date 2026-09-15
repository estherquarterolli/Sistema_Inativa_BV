<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#f4f0e8">
<title>Inativa aí — Gerador de planilha</title>
<link rel="stylesheet" href="/assets/app.css">
</head>
<body>
<main class="page-shell home-layout">
    <section class="intro" aria-labelledby="page-title">
        <a class="brand" href="/" aria-label="Inativa aí — início">
            <span class="brand-word">Inativa aí</span>
            <span class="brand-dot" aria-hidden="true"></span>
        </a>

        <p class="intro-copy">
            Envie a base principal e os arquivos com os nomes. A gente cruza os dados e prepara a planilha final para você.
        </p>

        <ul class="benefits" aria-label="Benefícios">
            <li><span class="benefit-mark" aria-hidden="true">✓</span> IDs preservados como texto</li>
            <li><span class="benefit-mark" aria-hidden="true">✓</span> Duplicidades removidas</li>
            <li><span class="benefit-mark" aria-hidden="true">✓</span> Arquivos apagados após o uso</li>
        </ul>
    </section>

    <section class="form-card" aria-labelledby="form-title">
        <p class="card-kicker">Vamos começar</p>
        <h2 id="form-title" class="card-title">Prepare sua planilha</h2>
        <p class="card-description">Escolha os dois grupos de arquivos abaixo. O processamento começa assim que você clicar no botão.</p>

        <?php if (!empty($erro)): ?>
        <div class="error-box" role="alert">
            <span aria-hidden="true">!</span>
            <span><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
        <?php endif; ?>

        <form id="upload-form" action="/processar" method="POST" enctype="multipart/form-data">
            <div class="form-step">
                <div class="step-heading">
                    <span class="step-number">1</span>
                    <h3>Base principal de alunos</h3>
                    <span>1 arquivo</span>
                </div>

                <input class="sr-only" id="main-file" type="file" name="planilha_principal" accept=".xlsx,.xls,.csv" required>
                <label class="drop-zone" for="main-file">
                    <span class="upload-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6M8 13h8M8 17h5"/>
                        </svg>
                    </span>
                    <span class="upload-copy">
                        <span class="upload-action">Clique ou arraste a planilha aqui</span>
                        <span class="upload-help" data-file-summary="main-file">XLSX, XLS ou CSV com as colunas Nome e ID</span>
                    </span>
                </label>
            </div>

            <div class="form-step">
                <div class="step-heading">
                    <span class="step-number">2</span>
                    <h3>Arquivos com nomes a inativar</h3>
                    <span>até 10 arquivos</span>
                </div>

                <input class="sr-only" id="source-files" type="file" name="documentos[]" accept=".pdf,.docx,.xlsx,.csv,.txt" multiple required>
                <label class="drop-zone" for="source-files">
                    <span class="upload-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 16V4M7.5 8.5 12 4l4.5 4.5"/>
                            <path d="M5 13v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-6"/>
                        </svg>
                    </span>
                    <span class="upload-copy">
                        <span class="upload-action">Clique ou arraste os arquivos aqui</span>
                        <span class="upload-help" data-file-summary="source-files">PDF, DOCX, XLSX, CSV ou TXT</span>
                    </span>
                </label>
                <p class="field-note">Nas planilhas, somente a coluna identificada como nome, aluno ou estudante entra na contagem.</p>
            </div>

            <button id="submit-button" class="primary-button" type="submit">
                <span>Gerar planilha</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </button>

            <p class="privacy-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                </svg>
                Seus arquivos são temporários e não ficam armazenados.
            </p>
        </form>
    </section>
</main>

<div id="processing-overlay" class="loading-overlay" role="status" aria-live="polite" aria-modal="true">
    <div class="loading-card">
        <div id="progress-ring" class="progress-ring">
            <span id="progress-value" class="progress-value">0%</span>
        </div>
        <h2>Organizando tudo por aqui</h2>
        <p id="progress-message">Preparando os arquivos...</p>
    </div>
</div>

<script src="/assets/home.js"></script>
</body>
</html>
