<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
// use Illuminate\Http\JsonResponse;

use App\Rules\DniRule;
use App\Rules\CelularRule;


class UsuarioValidation extends FormRequest
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
            'dni'=>['required',new DniRule(),
                Rule::unique('user')->where(function ($query) {
                    return $query->where('empresa_id',session('empresa_id'));
                })
            ],
            'nombre'=>['required','max:30'],
            'apellido'=>['required','max:50'],
            'numero'=>['required','max:12', new CelularRule()],
            'email'=>['required','max:100','email','unique:user,email'],
            'rol'=>['required'],
            'password'=>['required','max:100'],
        ];
    }

    public function messages(){
        return [
            'dni.unique'=> 'El usuario ya fue registrado.'
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
