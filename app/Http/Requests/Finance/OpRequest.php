<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;

class OpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if($this->option == 'op'){
            return [
                'customer_id' => 'sometimes|required',
                'collection_id' => 'sometimes|required',
                'payment_id' => 'sometimes|required',
                'selected' => 'required|array|min:1',
                'selected.*' => 'required|distinct|min:1'
            ];
        }else{
            return [];
        }
    }
}
