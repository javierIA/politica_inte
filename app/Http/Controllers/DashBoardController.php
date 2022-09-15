<?php

namespace App\Http\Controllers;

use App\Area;
use App\Box;
use App\FedDistrict;
use App\LocDistrict;
use App\Person;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{
    public static $methods = [
        'managePerson' => ['managePerson'],
        'assignRepresentingTable' => ['assignRepresentingTable']
    ];

    public function managePerson(){
        $title = trans('admin.person');
        $repo = Person::all();
        $columns = Person::getTableColumns();
        return view('admin.person.index', compact('title', 'repo', 'columns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function assignRepresentingTable(){
        $title = trans('admin.assignRepresentingTable');
        $columns = Box::getTableColumns();
        return view('admin.box.assign_representing_table', compact('title',  'columns'));
    }

    /**
     * Filter the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function representing_table_filter(Request $request){
        $data = $request->all();
        $exceptions = ['_token' => ''];
        $selects = [
            "(Select section_key FROM sections where id = id_section) as section_key",
            "(Select box_type_name from box_types where id = id_box_type) as box_type_name",
            "((Select section_key FROM sections where id = id_section) || ' - ' || (Select box_type_name from box_types where id = id_box_type) || box_index) as box_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = titular_person1) as tp_1_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = titular_person2) as tp_2_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = vocal_person) as vp_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = owner) as o_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = president) as president_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = secretary) as secretary_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = teller1) as t_1_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = teller2) as t_2_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = substitute1) as s_1_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = substitute2) as s_2_name",
            "(Select person_name || ' ' || father_lastname || ' ' || mother_lastname from persons where id = substitute3) as s_3_name",
            "null as selected"
        ];

        $info = [
            'columns' =>  ['selected' ,
                'id',
                'box_type_name',
                'id_address',
                'section_key',
                'box_index',
                'owner_name',
                'address_text',
                'box_name',
                'tp_1_name',
                'tp_2_name',
                'vp_name',
                'o_name',
                'president_name',
                'secretary_name',
                't_1_name',
                't_2_name',
                's_1_name',
                's_2_name',
                's_3_name',
                'titular_person1',
                'titular_person2',
                'vocal_person',
                'owner',
                'president',
                'secretary',
                'teller1',
                'teller2',
                'substitute1',
                'substitute2',
                'substitute3',
            ],
            'controller' => 'BoxController',
            'table' => with(new Box())->getTable(),
            'route' => [
                'script_function' =>[
                    'name' => 'representing_table.filter',
                    'text' => trans('admin.assign_representing'),
                    'function' => 'showRepresenting',
                    'icon' => 'entypo-users',
                    'data' => 'id'
                ]
            ]
        ];

        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function assignRepresenting($id){
        $box = Box::findOrFail($id);
        $title = trans('admin.assign_representing');
        $columns_person = Person::getTableColumns();
        return view('admin.box.assign_representing_dashboard', compact('title', 'box', 'columns_person'));
    }

    /**
     * Assign representatives a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveRepresenting(Request $request, $id){
        $box = Box::findOrFail($id);
        if(!is_null($box)){
            $items = $request->all();
            $box->fill($items);
            $box->update();
        }
        return redirect(route('dashboard.assignRepresentingTable'));
    }

    public function getChart($code, $from = null, $to = null, $view = true){
        $title = trans('admin.chart');
        $keys = array();
        $values = array();
        $dates = array();

        /*switch ($code){
            case 'epbm': //existent products by mail
                $title .=': ' . trans('admin.epbm');
                $repo = !$view? MailItem::whereNotNull('product_id')->whereBetween('created_at',array($from,$to))->get(): MailItem::whereNotNull('product_id')->get();

                $dates['from'] = date('Y-m-d',strtotime( MailItem::whereNotNull('product_id')->orderBy('created_at', 'asc')->first()->created_at));
                $dates['to'] =  date('Y-m-d',strtotime( MailItem::whereNotNull('product_id')->orderBy('created_at', 'desc')->first()->created_at));

                foreach ($repo as $item){
                    $n = $item->product;
                    settype($n, 'string');
                    if(!isset($values[$n]))
                        $values[$n] = 0;
                    $values[$n] = $values[$n]+1;
                }

                $keys = array_keys($values);
                $values = array_values($values);

                break;
            case 'epvsnep': //existent products vs no existent product requested by email
                $title .=': ' . trans('admin.epvsnep');
                $nregistered = !$view? MailItem::whereNull('product_id')->whereBetween('created_at',[$from,$to])->count() :MailItem::whereNull('product_id')->count();
                $registered = !$view? MailItem::whereNotNull('product_id')->whereBetween('created_at',[$from,$to])->count() :MailItem::whereNotNull('product_id')->count();

                $dates['from'] = date('Y-m-d',strtotime(MailItem::orderBy('created_at', 'asc')->first()->created_at));
                $dates['to'] =  date('Y-m-d',strtotime(MailItem::orderBy('created_at', 'desc')->first()->created_at));


                $keys = array(trans('admin.registered'),trans('admin.no_registered'));
                $values = array($registered, $nregistered);
                break;
        }*/
        if(!$view)
            return ['keys' => $keys, 'values' => $values];
        return view('admin.mail_item.chart', compact('title', 'keys', 'values', 'dates', 'code'));
    }

    public function updateChart(Request $request){
        return json_encode($this->getChart($request->input('code'),
            $request->input('from'),
            $request->input('to'),
            false));
    }
}
