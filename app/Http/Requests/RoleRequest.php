<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'project' => 'required',
            'user' => 'required|integer',
            'role' => 'in:ADMIN,EDITOR,SUBSCRIBER|nullable',
        ];
    }

    public function projectId() : int
    {
        if(!is_numeric($this->project)) {
            return decrypt($this->project);
        }
        return $this->project;
    }

    public function authorize(): bool
    {
        return true;
    }
}
