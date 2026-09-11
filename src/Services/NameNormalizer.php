<?php

namespace App\Services;

/**
 * Reproduz em PHP exatamente a normalizacao usada na formula do Google Sheets:
 *
 * =PROCV(REGEXREPLACE(REGEXREPLACE(REGEXREPLACE(REGEXREPLACE(REGEXREPLACE(REGEXREPLACE(
 *      MINUSCULA(ARRUMAR(A2));"[aaaa]";"a");"[eeee]";"e");"[iiii]";"i");"[oooo]";"o");"[uuuu]";"u");"[c]";"c"), ...)
 *
 * ARRUMAR = trim + colapso de espacos duplos. MINUSCULA = lowercase.
 * Em seguida cada grupo de vogais acentuadas e "c" cedilhado vira a letra simples.
 */
class NameNormalizer
{
    private const MAP = [
        '/[áàâãä]/u' => 'a',
        '/[éèêë]/u'  => 'e',
        '/[íìîï]/u'  => 'i',
        '/[óòôõö]/u' => 'o',
        '/[úùûü]/u'  => 'u',
        '/[ç]/u'     => 'c',
    ];

    public static function normalize(string $name): string
    {
        $name = trim($name);
        $name = preg_replace('/\s+/u', ' ', $name) ?? $name;
        $name = mb_strtolower($name, 'UTF-8');

        foreach (self::MAP as $pattern => $replacement) {
            $name = preg_replace($pattern, $replacement, $name) ?? $name;
        }

        return trim($name);
    }
}
