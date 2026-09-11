<?php

namespace App\Services\Extractors;

use PhpOffice\PhpWord\IOFactory;

class DocxExtractor implements ExtractorInterface
{
    public function extract(string $filePath): array
    {
        $phpWord = IOFactory::load($filePath);
        $lines = [];

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $lines[] = $this->extractText($element);
            }
        }

        return NameLineFilter::filter($lines);
    }

    private function extractText(mixed $element): string
    {
        if (method_exists($element, 'getText')) {
            $text = $element->getText();
            return is_array($text) ? implode(' ', $text) : (string) $text;
        }

        if (method_exists($element, 'getElements')) {
            $parts = [];
            foreach ($element->getElements() as $child) {
                $parts[] = $this->extractText($child);
            }
            return implode(' ', $parts);
        }

        return '';
    }
}
