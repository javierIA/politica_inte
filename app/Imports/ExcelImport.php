<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExcelImport implements ToCollection
{
    private $class;
    private $exceptions;

    /**
     * @param $class
     * @param array $exceptions
     */
    public  function __construct($class, $exceptions = []){
        $this->class = $class;
        $this->exceptions = $exceptions;
    }

    /**
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection)
    {
        try {
            $table = (new $this->class())->getTable();
            $columns = DB::table('information_schema.columns')
                ->select('column_name')
                ->where('table_name', $table)
                ->pluck('column_name')
                ->toArray();


            $pos = [];
            $data = [];
            $id_pos = -1;
            foreach ($collection as $key => $value) {
                if (in_array($value[0], $columns)) {
                    $count = 0;
                    foreach ($value as $v) {
                        if ($v == 'id')
                            $id_pos = $count;
                        else if (in_array($v, $columns))
                            $pos[$count] = $v;
                        $count++;
                    }
                } else {
                    foreach ($value as $key => $val) {
                        if ($key == $id_pos)
                            continue;
                        if (!empty($this->exceptions) && isset($this->exceptions[$pos[$key]]) && !empty($val)) {
                            $temp = DB::table($this->exceptions[$pos[$key]]['table'])
                                ->select('id')
                                ->where($this->exceptions[$pos[$key]]['param'], trim($val))
                                ->first();
                            $val = is_null($temp) ? null : $temp->id;
                        }
                        $data[$pos[$key]] = $val;
                    }
                    DB::table($table)->insert($data);
                }
            }
        }
        catch(\Exception $e){
            var_dump($e->getMessage());die;
        }
    }
}
