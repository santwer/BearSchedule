<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GanttExport implements FromView, WithStyles,WithColumnWidths, WithTitle
{
    protected $project;

    public function __construct(Project $project, ?Carbon $from = null, ?Carbon $to = null)
    {
        $project->loadMissing([
            'items' => function (HasMany $builder) use ($from, $to) {
                if($from) {
                    $builder->where('start', '>=', $from);
                }
                if($to) {
                    $builder->where('end', '<=', $to);
                }
            }, 'groups'
        ]);
        $this->project = $project;
    }


    public function view(): View
    {
        $interval = $this->interval();
        return view('export.gantt_export', [
            'project' => $this->project,
            'items' => $this->project->items,
            'groups' => $this->project->groups,
            'interval' => $interval,
            'months' => $this->monthsFromInterval($interval),
            'num2Alpha' => function ($num) {
                return self::num2AlphaExcel($num);
            },
            'dateTimeToExcel' => function (\DateTimeInterface $dateTime) {
                return Date::dateTimeToExcel($dateTime);
            }
        ]);
    }

    private function interval(): array
    {
        /**
         * @var $start Carbon
         */
        $start = $this->project->items->min('start');
        /**
         * @var $end Carbon
         */
        $end = $this->project->items->max('end');
        //get array with days between start and end
        $interval = [];
        for ($date = $start; $date->lte($end); $date->addDay()) {
            $interval[] = (clone $date);
        }

        return $interval;
    }

    private function monthsFromInterval(array $interval) : array
    {
        //get months and amount of days from array with all days as Carbon
        $months = [];
        foreach ($interval as $date) {
            $month = Date::dateTimeToExcel((clone $date)->startOfMonth());
            if (!isset($months[$month])) {
                $months[$month] = 0;
            }
            $months[$month]++;
        }

        return $months;
    }

    public static function num2AlphaExcel(int $col_num): string
    {
        $n = $col_num;
        for ($r = ""; $n >= 0; $n = intval($n / 26) - 1) {
            $r = chr($n % 26 + 0x41) . $r;
        }
        return $r;
    }

    public function styles(Worksheet $sheet)
    {
        //if column G6 is 1 set background color to red
        $interval = $this->interval();
        $cols = count($interval);
        $rows = $this->project->groups->count() + $this->project->items()->count();
        $lastCol = self::num2AlphaExcel($cols + 5);
        $lastRow = $rows + 5;

        for ($c = 5; $cols+5 >= $c; $c++) {
            $sheet->getColumnDimension(self::num2AlphaExcel($c))->setWidth(4);
        }

        $sheet->getStyle('G4:'.$lastCol . '4')->getNumberFormat()->setFormatCode('mmm yy');
        $sheet->getStyle('G5:'.$lastCol . '5')->getNumberFormat()->setFormatCode('dd');

        $sheet->getStyle('G4:'.$lastCol . '5')->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle('G4:'.$lastCol . '5')->getFill()->getStartColor()->setARGB('a1cdff');
        $sheet->getStyle('G4:'.$lastCol . '5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER_CONTINUOUS);

        $sheet->getStyle('C5:D'.$lastRow)->getNumberFormat()->setFormatCode(__('date_format_excel'));

        $sheet->getStyle('G6:' . $lastCol . $lastRow)->getFont()
            ->setColor(new Color(Color::changeBrightness('FFFFFF',0)));


        $this->setConditionalStyles($sheet, $lastCol, $lastRow);
    }

    private function setConditionalStyles(Worksheet $sheet, string $lastCol, int $lastRow)
    {
        $conditional = new Conditional();
        $conditional->setConditionType(Conditional::CONDITION_CELLIS);
        $conditional->setOperatorType(Conditional::OPERATOR_EQUAL);
        $conditional->addCondition(1);

        $conditional->getStyle()->getFont()->getColor()->setARGB('c0daff');
        $conditional->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $conditional->getStyle()->getFill()->getStartColor()->setARGB('c0daff');

        $conditionalStyles = $sheet->getStyle('G6:' . $lastCol . $lastRow)->getConditionalStyles();
        $conditionalStyles[] = $conditional;

        $sheet->getStyle('G6:' . $lastCol . $lastRow)->setConditionalStyles($conditionalStyles);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 10,
            'C' => 15,
            'D' => 15,
            'E' => 5,
            'F' => 5,
        ];
    }

    public function title(): string
    {
        return $this->project->name;
    }
}
