<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Revisar nomes extraídos</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen p-4">
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8 my-8">
    <h1 class="text-2xl font-semibold text-slate-800 mb-1">Revisar nomes extraídos</h1>
    <p class="text-slate-500 mb-6">
        Confira e corrija os nomes de cada documento antes de gerar a planilha final (um nome por linha).
        <?= (int) $totalAlunos ?> alunos carregados da planilha principal.
    </p>

    <form action="/gerar" method="POST" class="space-y-6">
        <?php foreach ($docs as $arquivo => $nomes): ?>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2"><?= htmlspecialchars($arquivo) ?></label>
            <textarea name="docs[]" rows="6"
                class="w-full text-sm border border-slate-200 rounded-lg p-3 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500"
            ><?= htmlspecialchars(implode("\n", $nomes)) ?></textarea>
            <p class="text-xs text-slate-400 mt-1"><?= count($nomes) ?> nome(s) identificado(s) automaticamente — ajuste se necessário.</p>
        </div>
        <?php endforeach; ?>

        <div class="flex gap-3">
            <a href="/" class="flex-1 text-center border border-slate-200 text-slate-600 font-medium py-2.5 rounded-lg hover:bg-slate-50 transition">
                Voltar
            </a>
            <button type="submit"
                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition">
                Gerar planilha
            </button>
        </div>
    </form>
</div>
</body>
</html>
