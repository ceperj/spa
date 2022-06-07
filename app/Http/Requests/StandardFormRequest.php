<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base for standard user requests. These are users intended
 * to use the application and its basic resources (e.g. register
 * project, register employee).
 */
class StandardFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isStandard();
    }
}