<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExperienceExport implements FromArray, WithHeadings
{
    private $data;
    private $headings;

    public function __construct(array $data, array $headings = null) 
    {
        $this->data = $data;
        $this->headings = $headings;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings ?? [];
    }
}
