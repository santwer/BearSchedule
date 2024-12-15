<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class GroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'content' => 'required',
            'title' => 'required',
            'project_id' => 'required|integer'
        ];
    }

    protected function prepareForValidation()
    {
        if(!is_numeric($this->project_id)) {
            $this->merge([
                'project_id' => decrypt($this->project_id)
            ]);
        }
        $this->merge([
            'title' => $this->get('content'),
        ]);

    }

    public function authorize(): bool
    {
        return Gate::allows('editProject', $this->project_id);
    }
}
