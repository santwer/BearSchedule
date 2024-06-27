<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class TimelineItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'project_id' => 'required|int',
            'title' => 'required',
            'group' => 'required_unless:type,=,background',
            'start' => 'required|date|lte:end',
            'end'   => 'date|nullable',
            'content' => 'nullable',
            'className' => 'nullable',
            'style' => 'nullable',
            'align' => 'nullable',
            'selectable' => 'nullable',
            'subgroup' => 'nullable',
            'type' => 'nullable',
            'limitSize' => 'nullable',
            'editable' => 'nullable',
            'description' => 'nullable',
            'subtitle' => 'nullable',
            'status' => 'nullable',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'start' => $this->start ? Carbon::createFromFormat('D M d Y H:i:s e+',$this->start)->format('Y-m-d H:i:s') : null,
            'end' => $this->end ? Carbon::createFromFormat('D M d Y H:i:s e+',$this->end)->format('Y-m-d H:i:s') : null,
        ]);
    }

    public function start()
    {
        return $this->start ?
            Carbon::parse($this->start)->startOfDay()
            : null;
    }

    public function end()
    {
        return $this->end ?
            Carbon::parse($this->end)->endOfDay()
            : null;
    }


}
