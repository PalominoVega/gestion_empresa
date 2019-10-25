<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
// use Illuminate\Http\JsonResponse;

use App\Rules\RucRule;
use App\Rules\CelularRule;


class LandingEmpresaValidation extends FormRequest
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
            'nombre'=>'required|max:30',
            'nombre_empresa'=>'required|max:150',
            'ruc'=>['required','max:30', new RucRule(),'unique:empresa,ruc'],
            'email'=>'required|email|max:100|unique:user,email',
            'telefono'=>['required','max:15',new CelularRule()],
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
