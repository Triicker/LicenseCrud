<?php

namespace App\Http\Controllers;

use App\Models\Zwnempresalayout;
use App\Models\Zwnempresa;

use Illuminate\Http\Request;

class LayoutController extends Controller
{
    public function index()
    {
        $empresaID = session('IDEMPRESA');

        $empresa = Zwnempresa::find($empresaID);

        if (!$empresa) {
            return view('layout', ['empresaStyle' => null]);
        }

        $empresaStyle = [
            'cor_menu_superior' => $empresa->cor_menu_superior, 
            'cor_menu_lateral' => $empresa->cor_menu_lateral, 
            'cor_hover_menu_lateral' => $empresa->cor_hover_menu_lateral, 
            'cor_fundo_logo_menu' => $empresa->cor_fundo_logo_menu, 
            'cor_hover_menu_sup' => $empresa->cor_hover_menu_sup, 
            'cor_fonte_icon_lateral' => $empresa->cor_fonte_icon_lateral, 
            'cor_fonte_icon_sup' => $empresa->cor_fonte_icon_sup, 
            'logoUrlFromDatabase' => $empresa->logoUrl, 
        ];

        return view('layout', compact('empresaStyle'));
    }
}
