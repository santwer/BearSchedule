<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class ExcelExportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start' => 'nullable|date',
            'end_date' => 'nullable|date',
        ];
    }

    public function startDate() : ?Carbon
    {
        return $this->start ? Carbon::parse($this->start) : null;
    }

    public function endDate() : ?Carbon
    {
        return $this->end ? Carbon::parse($this->end) : null;
    }

    public function authorize(): bool
    {
        return true;
    }
}
