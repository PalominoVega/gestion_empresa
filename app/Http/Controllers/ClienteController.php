<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Peru\Jne\Dni;
use Peru\Sunat\Ruc;
use Peru\Http\ContextClient;

class ClienteController extends Controller
{
    public function search(Request $request){
        if ($request->dni!=null) {
            $dni = $request->dni;
    
            $cs = new Dni();
            $cs->setClient(new ContextClient());
    
            $person = $cs->get($dni);
            if ($person === false) {
                echo $cs->getError();
                exit();
            }
    
            echo json_encode($person);
        }
        if ($request->ruc!=null) {
            $ruc = $request->ruc;

            $cs = new Ruc();
            $cs->setClient(new ContextClient());
            
            $company = $cs->get($ruc);
            if ($company === false) {
                echo $cs->getError();
                exit();
            }
            
            echo json_encode($company);
        }
    }
}
