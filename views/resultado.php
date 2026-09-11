<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Planilha gerada</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-xl bg-white rounded-2xl shadow-sm border border-slate-200 p-8 text-center">
    <div class="mx-auto w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
        <span class="text-emerald-600 text-xl">✓</span>
    </div>
    <h1 class="text-2xl font-semibold text-slate-800 mb-2">Planilha gerada com sucesso</h1>
    <p class="text-slate-500 mb-6">
        <?= (int) $encontrados ?> de <?= (int) $total ?> nomes foram localizados e incluídos na planilha.
    </p>

    <a href="/baixar?token=<?= htmlspecialchars($token) ?>"
       class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2.5 rounded-lg transition mb-6">
        Baixar planilha (.xlsx)
    </a>

    <?php if (!empty($naoEncontrados)): ?>
    <div class="text-left mt-6">
        <h2 class="text-sm font-medium text-slate-700 mb-2">
            <?= count($naoEncontrados) ?> nome(s) não encontrados na planilha principal:
        </h2>
        <ul class="text-sm text-slate-500 bg-slate-50 border border-slate-200 rounded-lg p-3 max-h-48 overflow-y-auto list-disc list-inside">
            <?php foreach ($naoEncontrados as $nome): ?>
            <li><?= htmlspecialchars($nome) ?></li>
            <?php endforeach; ?>
        </ul>
        <p class="text-xs text-slate-400 mt-2">Esses nomes também ficam em uma aba separada dentro da planilha baixada.</p>
    </div>
    <?php endif; ?>

    <a href="/" class="block mt-6 text-sm text-indigo-600 hover:underline">Processar novo lote</a>
</div>
</body>
</html>
