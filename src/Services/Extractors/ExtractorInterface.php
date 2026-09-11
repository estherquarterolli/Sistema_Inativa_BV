<?php

namespace App\Services\Extractors;

interface ExtractorInterface
{
    /**
     * @return string[] lista de linhas candidatas a nomes de alunos
     */
    public function extract(string $filePath): array;
}
