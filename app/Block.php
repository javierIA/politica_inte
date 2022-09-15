<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Block extends Model
{
    protected $fillable = [
        'block_key',
        'section_id'
    ];

    public function titular_person($id)
    {
        $block = $this::findOrFail($id);
        return Person::findOrFail($block->titular_person);
    }

    public function vocal_person($id)
    {
        $block = $this::findOrFail($id);
        return Person::findOrFail($block->vocal_person);
    }

    public static function getTableColumns() {

        $person = DB::table('persons')
            ->select(
                DB::raw("persons.person_name || ' ' || persons.father_lastname || ' ' || persons.mother_lastname AS full_name"),
                'persons.id'
            )->pluck('full_name','id')->toArray();
        $sections = DB::table('sections')
            ->select('section_key','id')
            ->pluck('section_key','id')->toArray();

        $items = ['urban' => trans('admin.urban'), 'rural' => trans('admin.rural'), 'mixed' => trans('admin.mixed'),];


        return [
            'block_key'=> Controller::getComponent('block_key', trans('admin.block_key')),
            'titular_person'=> Controller::getComponent('titular_person', trans('admin.titular_person'),$person),
            'vocal_person'=> Controller::getComponent('vocal_person', trans('admin.vocal_person'),$person),
            'section_id'=> Controller::getComponent('section_id', trans('admin.section'),$sections),
        ];
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
