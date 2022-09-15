<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;

class LanguageController extends Controller
{
    public function swap($lang){
        session()->put('locale',$lang);
        return redirect()->back();
    }

    public function swapcurrency($c){
        $cur = Currency::whereCode($c)->first();
        session()->remove('currency');
        session()->push('currency',array('id'   =>$cur->id,
            'code' =>$cur->code,
            'name' =>$cur->name,
            'rate' =>$cur->rate));
        return redirect()->back();
    }
}
