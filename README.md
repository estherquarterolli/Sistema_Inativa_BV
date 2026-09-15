# Gerador de Planilha de Inativação

Ferramenta web 100% gratuita (PHP + bibliotecas open-source) que substitui o
processo manual de planilhas para gerar a lista de IDs a inativar.

## Como funciona

1. Você anexa a **planilha principal de alunos** (sempre atualizada), a
   mesma que hoje é referenciada pelo `IMPORTRANGE` na sua fórmula.
2. Você anexa até **10 documentos** (PDF, DOCX, XLSX, CSV ou TXT) contendo os
   nomes dos alunos a inativar.
3. O sistema extrai os nomes de cada documento. Em planilhas XLSX/CSV, somente
   uma coluna com cabeçalho reconhecido como nome/aluno/estudante é lida; abas
   e arquivos sem essa coluna não entram na contagem.
4. Em seguida, ele normaliza cada nome exatamente como a sua fórmula do
   Google Sheets fazia (minúsculas, sem acento, sem espaço duplo), procura o
   ID correspondente na planilha principal e gera um `.xlsx` final:
   - Aba **"Conteúdo"**: coluna `PESSOA ID`, formatada como **texto simples**
     (igual ao seu modelo `Inativacao_modelo_.xlsx`).
   - Aba **"Não Encontrados"**: nomes que não bateram com nenhum ID, para
     você conferir manualmente.

Nenhum dado é salvo permanentemente: os uploads são apagados assim que
processados, e o arquivo gerado é apagado do servidor assim que baixado.

## Arquitetura

Organizado em MVC simples, sem framework pesado (mantém tudo gratuito e fácil
de hospedar em qualquer servidor PHP comum):

```
public/index.php          Front controller + rotas
src/Controllers/          Upload, geração e download
src/Services/              Regras de negócio (normalização, leitura da
                            planilha, extratores de documento, matching,
                            geração do arquivo final)
src/Services/Extractors/   Um extrator por tipo de arquivo (PDF/DOCX/XLSX/CSV/TXT)
src/Models/Student.php     Modelo do aluno
views/                     HTML + Tailwind (via CDN, sem build step)
storage/uploads|output/    Arquivos temporários
```

## Requisitos

- PHP 8.1 ou superior, com as extensões `mbstring`, `xml`, `zip`, `gd`
  (as mesmas exigidas pelo PhpSpreadsheet).
- [Composer](https://getcomposer.org) para instalar as dependências.

Bibliotecas usadas (todas open-source/MIT, sem custo e sem chave de API):

- [`phpoffice/phpspreadsheet`](https://github.com/PHPOffice/PhpSpreadsheet) — ler/gerar `.xlsx`
- [`phpoffice/phpword`](https://github.com/PHPOffice/PHPWord) — ler `.docx`
- [`smalot/pdfparser`](https://github.com/smalot/pdfparser) — ler `.pdf`

## Instalação

```bash
composer install
```

## Como rodar localmente (sem precisar de Apache/Nginx)

```bash
php -S localhost:8000 -t public public/index.php
```

Acesse `http://localhost:8000`.

## Como colocar em um servidor com Apache

Aponte o `DocumentRoot` para a pasta `public/` (o `.htaccess` já está
configurado). Garanta que `storage/uploads` e `storage/output` tenham
permissão de escrita pelo usuário do PHP (`chmod -R 775 storage`).

## Ajustando limites de upload

Como o app aceita a planilha principal + 10 documentos, verifique no seu
`php.ini` (ou em um `.user.ini` na pasta `public/`):

```ini
upload_max_filesize = 20M
post_max_size = 25M
max_file_uploads = 20
```

## Publicação gratuita na Vercel

O projeto inclui `vercel.json`, `api/index.php` e `api/php.ini` preparados para
o runtime comunitário `vercel-php` com PHP 8.5. Para publicar, importe este
repositório no painel da Vercel e mantenha as configurações detectadas.

Como as Functions não garantem arquivos temporários entre requisições, na
Vercel a planilha final é incorporada à página de resultado e baixada
diretamente pelo navegador. Localmente, o download por token continua sendo
usado normalmente.

A Vercel limita o corpo de uma requisição de Function a 4,5 MB. Por segurança,
a interface limita a soma dos arquivos enviados a 4 MB quando detecta o
ambiente da Vercel.

## Sobre a coluna de nome/ID na planilha principal

O sistema tenta detectar automaticamente, pelo cabeçalho, qual coluna tem o
nome e qual tem o ID (procurando por "nome"/"aluno" e por "id" no texto do
cabeçalho). Se não encontrar, ele usa o padrão da sua fórmula original:
coluna **A** = nome, coluna **C** = ID. Se sua planilha tiver uma aba
chamada **"Dados"**, essa aba é usada automaticamente (igual ao
`IMPORTRANGE(...;"Dados!A:C")` que você já usava).

## Extensões possíveis (não incluídas, para manter o projeto simples)

- Autenticação/login, caso vá publicar a ferramenta para outras pessoas.
- Histórico de lotes processados (hoje é "stateless": cada geração é isolada).
- Busca "aproximada" (fuzzy match) para nomes com pequenas diferenças de
  digitação — hoje esses casos aparecem na lista de nomes não encontrados.
