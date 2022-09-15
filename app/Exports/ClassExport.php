<?php

namespace App\Exports;

use App\Group;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Excel;

class ClassExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $class;
    private $query;
    private $columns;
    public  function __construct($class, $query = null, $exception = []){
        $this->class = $class;
        $this->query = $query;

        $var_types = DB::table('information_schema.columns')
            ->select('column_name','data_type')
            ->where('table_name',(new $this->class())->getTable())
            ->pluck('data_type','column_name')->toArray();

        $this->columns = array_diff(array_keys($var_types),array_merge(["id", "created_at", "updated_at"], $exception));
    }

    /**
    * @return Collection
    */
    public function collection()
    {
        $string_types = ['text','char','character varying'];
        $date_types = ['timestamp without time zone','date'];
        $var_types = DB::table('information_schema.columns')
            ->select('column_name','data_type')
            ->where('table_name',(new $this->class())->getTable())
            ->pluck('data_type','column_name')->toArray();
        $data = $this->class::all($this->columns);

        if(count($this->query)>0){
            $data = $this->class::query();
            foreach ($this->query as $key => $val){
                if($key == 'raw' )
                    $data->whereRaw($val);
                else if(in_array($var_types[$key], $string_types))
                    $data->where($key,'LIKE',"%$val%");
                else if(in_array($var_types[$key], $date_types)){
                    $dtime = \DateTime::createFromFormat("d/m/Y", $val);
                    $temp = "to_char($key, 'DD-MM-YYYY') = "."'".date_format($dtime, 'd-m-Y')."'";
                    $data->whereRaw($temp);
                }
                else
                    $data->where($key,$val);
            }
            $data = $data->get($this->columns);
        }
        return $data;
    }

    public function headings(): array
    {
        return DB::table('information_schema.columns')
            ->select('column_name')
            ->where('table_name',(new $this->class())->getTable())
            ->whereIn('column_name', $this->columns)
            ->pluck('column_name')
            ->toArray();
    }
}
