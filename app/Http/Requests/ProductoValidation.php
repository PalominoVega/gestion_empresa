<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Model\Producto;

class ProductoValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'nombre'    => 'required|max:100',
            'nombre'    => [
                'required',
                'max:100',
                function($attribute, $value, $fail) {
                    $producto=Producto::where('nombre',$value)
                        ->where('empresa_id',session('empresa_id'))->first();
                    if ($producto!=null) {
                        $fail($value.' ya fue registrado.');
                    }
                }
            ],   
        ];
    }

    public function messages(){
        return [
            'nombre.unique'=> 'El producto ya fue registrado.'
        ];
    }

    protected function failedValidation(Validator $validator) {
        //extraer array
        $sin_array=str_replace(["[","]"], "",json_encode($validator->errors()));
        
        throw new HttpResponseException(response()->json([
            "status" => "VALIDATION",
            "data"   =>  json_decode($sin_array)
        ], 200));
    }
}
