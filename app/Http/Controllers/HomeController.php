<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'home';
        $select = DB::select("SELECT 
                                (SELECT count(*) FROM persons where person_sex = 'f') as sex_f,
                                (SELECT count(*) FROM persons where person_sex = 'm') as sex_m,
                                (SELECT count(*) FROM persons where is_working = '1') as working,
                                (SELECT count(*) FROM persons where is_studying = '1') as studying,
                                (SELECT count(*) FROM persons where territory_volunteer = '1') as t_volunteer,
                                (SELECT count(*) FROM persons where electoral_volunteer = '1') as e_volunteer,
                                (Select count(*) From persons where SUBSTRING(validity, 1, 1) = '1') as valitity_email,
                                (Select count(*) From persons where SUBSTRING(validity, 2, 1) = '1') as valitity_cellphone,
                                (Select count(*) From persons where SUBSTRING(validity, 3, 1) = '1') as valitity_id");
        if (count($select) > 0) $response = $select[0];

        $graphic_data = [
            'chart_keys' => [
                trans('admin.woman'),
                trans('admin.man'),
                trans('admin.workers'),
                trans('admin.students'),
                trans('admin.territorial_volunteer'),
                trans('admin.electoral_volunteer')],
            'chart_values' => [
                $response->sex_f,
                $response->sex_m,
                $response->working,
                $response->studying,
                $response->t_volunteer,
                $response->e_volunteer,
            ],
            'doughnut_data'=>[
                $response->valitity_email,
                $response->valitity_cellphone,
                $response->valitity_id,
            ],
            'doughnut_color'=>[
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            'doughnut_labels'=>[
                trans('admin.email'),
                trans('admin.person_cellphone'),
                trans('admin.card_pdf'),
            ]
         ];

        return view('admin.dashboard', compact('title', 'graphic_data'));
    }
}
