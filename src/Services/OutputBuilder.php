<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OutputBuilder
{
    /**
     * @param array<int, array{nome: string, id: string}> $matched
     * @param string[] $unmatched
     */
    public function build(array $matched, array $unmatched, string $outputPath): void
    {
        $spreadsheet = new Spreadsheet();

        $this->buildIdSheet($spreadsheet->getActiveSheet(), $matched);

        if (!empty($unmatched)) {
            $sheet2 = $spreadsheet->createSheet();
            $this->buildNotFoundSheet($sheet2, $unmatched);
        }

        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $writer->save($outputPath);
    }

    private function buildIdSheet(Worksheet $sheet, array $matched): void
    {
        $sheet->setTitle('Conteúdo');
        $sheet->setCellValue('A1', 'PESSOA ID');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9D9D9');

        $row = 2;
        foreach ($matched as $item) {
            $cell = $sheet->getCell('A' . $row);
            $cell->setDataType(DataType::TYPE_STRING);
            $cell->setValue($item['id']);
            $row++;
        }

        // Coluna inteira como texto simples, para nao perder zeros a esquerda
        $sheet->getStyle('A2:A' . max($row - 1, 2))
            ->getNumberFormat()
            ->setFormatCode('@');

        $sheet->getColumnDimension('A')->setWidth(40);
    }

    private function buildNotFoundSheet(Worksheet $sheet, array $unmatched): void
    {
        $sheet->setTitle('Não Encontrados');
        $sheet->setCellValue('A1', 'Nome não localizado na planilha principal');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $row = 2;
        foreach ($unmatched as $name) {
            $sheet->setCellValue('A' . $row, $name);
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(50);
    }
}
