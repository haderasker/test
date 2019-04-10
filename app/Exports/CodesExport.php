<?php
/**
 * Created by PhpStorm.
 * User: hadeer
 * Date: 4/10/18
 * Time: 5:25 PM
 */

namespace App\Exports;

use App\Code;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle ;

class CodesExport implements  FromQuery ,  WithMapping , WithHeadings , WithTitle
{
    use Exportable;

    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }


    public function query()
    {
        return Code::query();
    }

    public function map($code): array
    {
        return [
            $code->id ,
            $code->code,
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code',
        ];
    }

    public function title(): string
    {
        return 'Codes Of ' . $this->name;
    }
}
