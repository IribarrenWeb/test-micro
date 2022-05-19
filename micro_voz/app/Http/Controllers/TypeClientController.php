<?php

namespace App\Http\Controllers;

use App\Models\TypeClient;
use Illuminate\Http\Request;

class TypeClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        try {

            $types = TypeClient::all();

            return $this->success($types);
        } catch (\Exception $e) {
            $code = $this->getCleanCode($e);
            return $this->error('Error al Listar los tipos', $code);
        }
    }
}
