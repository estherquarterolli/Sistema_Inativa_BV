<?php

namespace App\Services\Extractors;

class NameLineFilter
{
    /**
     * Filtra linhas de texto mantendo apenas as que parecem nomes de
     * pessoas: só letras/espaços/apóstrofos/hífens, pelo menos duas
     * palavras, tamanho razoável. O objetivo é reduzir ruído (titulos,
     * numeros, cabecalhos) antes da etapa de revisao manual.
     *
     * @param string[] $lines
     * @return string[]
     */
    public static function filter(array $lines): array
    {
        $result = [];

        foreach ($lines as $line) {
            $line = trim((string) $line);

            if ($line === '' || mb_strlen($line) > 80) {
                continue;
            }

            if (!preg_match("/^[\p{L}][\p{L}\s'\-.]+$/u", $line)) {
                continue;
            }

            if (str_word_count($line) < 2) {
                continue;
            }

            $result[] = $line;
        }

        return array_values(array_unique($result));
    }
}
