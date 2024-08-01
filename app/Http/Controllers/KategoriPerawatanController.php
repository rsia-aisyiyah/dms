<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\KategoriPerawatan;
use Illuminate\Http\Request;

class KategoriPerawatanController extends Controller
{
    //
    public function getAllKategori(Request $request){
        if($request->ajax()){
            return $kategori = KategoriPerawatan::get();
        }else{
            return redirect()->intended('/');
        }
    }

    public function getKategoryByName(Request $request)
    {
        if($request->ajax()){
            $kategori = KategoriPerawatan::where('nm_kategori', 'like', '%'.$request->kategori.'%')->get();
            $output = '<ul class="dropdown-menu" style="width:auto;display:inline; position:'.$request->attr.'; border-radius:0">';
            foreach ($kategori as $row) {
                $output .= '
                <li ><a class="dropdown-item" href="#" style="overflow:hidden">' . $row->kd_kategori. ' - ' . $row->nm_kategori . '</a></li>
                ';
            }

            $output .= '</ul>';
            return $output;
        }else{
            return redirect()->intended('/');
        }
    }
}
