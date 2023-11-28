<?php

namespace App\Http\Controllers;

use App\Models\Zwnempresalayout;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function index()
    {
        $empresaID = session('IDEMPRESA');

        $empresa = Zwnempresalayout::find($empresaID);

        if (!$empresa) {
            return view('layout', ['empresaStyle' => null]);
        }
        
        $empresaStyle = $empresa->toArray();

        return view('layout', compact('empresaStyle'));
    }
}
