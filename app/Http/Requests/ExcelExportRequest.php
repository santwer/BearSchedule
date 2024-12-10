<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class ExcelExportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ];
    }

    public function startDate() : ?Carbon
    {
        return $this->start_date ? Carbon::parse($this->start_date) : null;
    }

    public function endDate() : ?Carbon
    {
        return $this->end_date ? Carbon::parse($this->end_date) : null;
    }

    public function authorize(): bool
    {
        return true;
    }
}
