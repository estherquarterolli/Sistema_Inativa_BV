# Modificações realizadas no Sistema Inativa BV

Este documento resume as alterações feitas para que o sistema funcione localmente, tenha uma interface mais simples e possa ser publicado gratuitamente na Vercel.

## 1. Ambiente local

- Instalação do PHP 8.5.10 no Windows.
- Instalação do Composer 2.10.3 e das dependências do projeto.
- Ativação das extensões exigidas pelas bibliotecas de leitura de documentos e planilhas.
- Criação do arquivo `iniciar.bat`, que inicia o sistema em `http://localhost:8000` sem precisar de Apache ou Nginx.
- Criação do `composer.lock`, garantindo que as mesmas versões das dependências sejam instaladas em outros ambientes.

## 2. Correção da leitura e da contagem de nomes

- Remoção da antiga etapa e da rota `/revisar`. Agora o envio, a comparação e a geração da planilha acontecem em um único fluxo.
- A quantidade exibida no resultado passou a considerar somente os nomes realmente lidos nos arquivos enviados pelo usuário.
- Em arquivos XLSX, o sistema:
  - procura uma coluna de nomes em todas as abas;
  - procura o cabeçalho nas primeiras 30 linhas, permitindo títulos antes da tabela;
  - reconhece cabeçalhos como `Nome`, `Nome do aluno`, `Aluno` e `Estudante`;
  - ignora abas que não possuem uma coluna de nomes reconhecida.
- Em arquivos CSV, o sistema também ignora o arquivo quando não encontra uma coluna de nomes reconhecida, em vez de presumir que a coluna A contém nomes.
- A tela de resultado mostra quantos nomes foram lidos em cada arquivo enviado.
- Os nomes que não existem na base principal são mostrados separadamente e também incluídos na aba `Não Encontrados` da planilha final.
- IDs continuam sendo gravados como texto, preservando zeros à esquerda.

Arquivos principais envolvidos:

- `src/Services/Extractors/XlsxExtractor.php`
- `src/Services/Extractors/CsvExtractor.php`
- `src/Controllers/UploadController.php`
- `views/resultado.php`

## 3. Novo design e experiência de uso

- Criação da identidade visual `Inativa aí`, com lettering e uma aparência mais humana.
- Reformulação completa das telas inicial e de resultado.
- Organização do envio em duas etapas visuais: base principal e arquivos com os nomes.
- Suporte a seleção por clique e por arrastar e soltar.
- Exibição dos nomes e da quantidade de arquivos selecionados.
- Layout responsivo e compacto para caber em uma única tela em notebooks, sempre que a altura disponível permitir.
- Inclusão de mensagens de erro e indicações mais claras sobre formatos aceitos, privacidade e resultado do processamento.
- Remoção da dependência visual do Tailwind via CDN; o estilo agora está no próprio projeto.

Arquivos principais envolvidos:

- `views/home.php`
- `views/resultado.php`
- `public/assets/app.css`
- `public/assets/home.js`

## 4. Indicador de processamento

- Depois de clicar em `Gerar planilha`, uma tela de carregamento é exibida.
- O círculo mostra a porcentagem do envio dos arquivos.
- Depois do envio, a animação continua durante a leitura das planilhas e a comparação dos nomes.
- Ao concluir, o indicador chega a 100% e abre automaticamente a tela de resultado.
- O botão fica desabilitado durante o processamento para evitar envios duplicados.

## 5. Adaptação para a Vercel

A Vercel executa PHP por meio de um runtime comunitário e não permite gravar arquivos permanentemente dentro do projeto. Para atender a essas condições, foram feitas as seguintes adaptações:

- Criação do ponto de entrada serverless `api/index.php`.
- Criação do `vercel.json`, configurado com:
  - runtime `vercel-php@0.9.0`, compatível com PHP 8.5;
  - 1 GB de memória para a função;
  - duração máxima de 60 segundos;
  - encaminhamento das rotas da aplicação para a função PHP;
  - entrega dos arquivos CSS e JavaScript diretamente da pasta pública.
- Criação do `api/php.ini` com limites de memória, tempo e upload adequados ao processamento.
- Criação da classe `src/Core/Storage.php` para usar:
  - `storage/` durante a execução local;
  - `/tmp` durante a execução na Vercel.
- Alteração dos controllers para sempre remover os uploads temporários após a leitura, inclusive quando ocorre um erro.
- Na execução local, a planilha final continua sendo baixada pela rota `/baixar` e é apagada depois do download.
- Na Vercel, a planilha final é convertida temporariamente em um link de download incorporado à página. Assim, o download não depende de o arquivo continuar existindo em outra execução serverless.
- Criação do `.vercelignore` para não enviar o iniciador local nem arquivos temporários de upload e saída.
- Validação no navegador para limitar a soma dos arquivos a 4 MB na Vercel. Essa margem respeita o limite de 4,5 MB do corpo das requisições das Functions.

Arquivos criados para essa adaptação:

- `vercel.json`
- `.vercelignore`
- `api/index.php`
- `api/php.ini`
- `src/Core/Storage.php`

Arquivos atualizados:

- `src/Controllers/UploadController.php`
- `src/Controllers/DownloadController.php`
- `views/home.php`
- `views/resultado.php`
- `public/assets/home.js`
- `public/assets/app.css`
- `README.md`

## 6. Validações realizadas

- Verificação de sintaxe de todos os arquivos PHP.
- Verificação de sintaxe do JavaScript.
- Validação do `composer.json` e do `composer.lock`.
- Validação do formato JSON do `vercel.json`.
- Testes locais de envio, leitura, comparação e download usando planilhas de exemplo.
- Teste simulando o ambiente da Vercel, incluindo o armazenamento em `/tmp` e o download incorporado à página.
- Confirmação de que IDs com zeros à esquerda continuam preservados.
- Confirmação de que planilhas sem coluna de nome não aumentam a contagem.

## 7. Como publicar na Vercel

1. Enviar estas alterações para o GitHub.
2. Na Vercel, escolher `Add New` e depois `Project`.
3. Importar o repositório `Sistema_Inativa_BV`.
4. Selecionar `Other` como Framework Preset.
5. Deixar `Build Command` e `Output Directory` vazios.
6. Clicar em `Deploy`.

No plano gratuito Hobby, o projeto deve ser usado para finalidade pessoal ou não comercial. Também é importante manter os arquivos enviados abaixo do limite de 4 MB mostrado pela interface.

Referências:

- [Runtimes de Functions da Vercel](https://vercel.com/docs/functions/runtimes)
- [Runtime comunitário PHP para Vercel](https://github.com/vercel-community/php)
- [Limite de payload das Functions](https://vercel.com/docs/errors/function_payload_too_large)
- [Plano gratuito Hobby](https://vercel.com/docs/plans/hobby)
