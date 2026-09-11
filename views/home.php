<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerador de Planilha de Inativação</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-800">Gerador de Planilha de Inativação</h1>
        <p class="text-slate-500 mt-1">Anexe a planilha principal de alunos e os documentos com os nomes a inativar.</p>
    </div>

    <?php if (!empty($erro)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
        <?= htmlspecialchars($erro) ?>
    </div>
    <?php endif; ?>

    <form action="/processar" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">1. Planilha principal de alunos</label>
            <input type="file" name="planilha_principal" accept=".xlsx,.xls,.csv" required
                   class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer border border-slate-200 rounded-lg">
            <p class="text-xs text-slate-400 mt-1">Precisa ter uma coluna de nome e uma coluna de ID (ex.: "Nome" e "Pessoa ID").</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">2. Documentos com os nomes (até 10 arquivos)</label>
            <input type="file" name="documentos[]" accept=".pdf,.docx,.xlsx,.csv,.txt" multiple required
                   class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 file:cursor-pointer border border-slate-200 rounded-lg">
            <p class="text-xs text-slate-400 mt-1">Formatos aceitos: PDF, DOCX, XLSX, CSV ou TXT.</p>
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition">
            Continuar
        </button>
    </form>
</div>
</body>
</html>
