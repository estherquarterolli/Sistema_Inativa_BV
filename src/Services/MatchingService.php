<?php

namespace App\Services;

class MatchingService
{
    /**
     * @param string[] $names
     * @param array<string, string> $index nome_normalizado => id
     * @return array{matched: array<int, array{nome: string, id: string}>, unmatched: string[]}
     */
    public function match(array $names, array $index): array
    {
        $matched = [];
        $unmatched = [];
        $seen = [];

        foreach ($names as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }

            $normalized = NameNormalizer::normalize($name);

            if (isset($seen[$normalized])) {
                continue; // evita duplicidade se o mesmo nome aparecer em mais de um documento
            }
            $seen[$normalized] = true;

            if (isset($index[$normalized])) {
                $matched[] = ['nome' => $name, 'id' => $index[$normalized]];
            } else {
                $unmatched[] = $name;
            }
        }

        return ['matched' => $matched, 'unmatched' => $unmatched];
    }
}
