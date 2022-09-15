<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
Use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'person_id',
        'email',
        'temp_address',
        'avatar',
        'password',
    ];

    public static function getTableColumns() {
        return [
            'name'=> Controller::getComponent('name', trans('admin.name')),
            'person_id'=> Controller::getComponent('person_id', trans('admin.person_name'), Person::pluck('person_name', 'id')->toArray()),
            'email'=> Controller::getComponent('email', trans('admin.email'))
        ];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password', 'remember_token',
    ];

    public function roleArray()
    {
        $r = $this->roles()->get();
        $roles = [];
        foreach($r as $rol)
            $roles[] = $rol->id;
        $roles = Role::wherein('id',$roles)->get();

        $sist_function = array();
        foreach($roles as $rol){
            $items = $rol->system_functions()->get();

            foreach($items as $it)
                if(!in_array($it->system_function_name,$sist_function))
                    $sist_function[]=$it->system_function_name;
        }
        return $sist_function;
    }

    public function authorizedRoles($roles){
        if($this->hasAnyRole($roles))
            return true;
        abort(401,'Esta acción no está autorizada');
    }

    public function hasAnyRole($roles){
        if(is_array($roles)){
            foreach($roles as $role){
                if($this->hasRole($role))
                    return true;
            }
        } else {
            if($this->hasRole($roles))
                return true;
        }
        return false;
    }

    public function hasRole($role){
        if($this->roles()->where('name',$role)->first()){
            return true;
        }
        return false;
    }

    public function get_Permission($system_function=null, $access = null){
        $roles = $this->roles()->get(['roles.id'])->pluck('id')->toArray();
        $permission = DB::table('role_system_function')
            ->Join('system_functions as sf','role_system_function.system_function_id','=','sf.id')
            ->whereIn('role_system_function.role_id', $roles)
            ->pluck('methods','system_function_name')->toArray();
        foreach ($permission as $key => $val)
            $permission[$key] = json_decode($val);

        if(!is_null($system_function)){
            if(isset($permission[$system_function]) && !is_null($access)){
                $def = Controller::$default_methods;
                foreach ($def as $key => $val)
                    if(in_array($access,$val) ){
                        $access = $key;
                        break;
                    }
                $meth =  Controller::methodsByController($system_function);

                foreach ($meth as $key => $val)
                    if(in_array($access,$val) ){
                        $access = $key;
                        break;
                    }
                return in_array($access,$permission[$system_function]);
            }
            return isset($permission[$system_function]);
        }
        return $permission;
    }

    public function jsonUser(){
        $keys = \Schema::getColumnListing($this->getTable());
        $params = $this->toArray();
        $user = array();

        unset($keys['password']);
        foreach($keys as $key) {
            if (isset($params[$key]))
                $user[$key] = $params[$key];
        }
        return json_encode($user);
    }

    public function jsonRole(){
        $rol = $this->roles()->get()->first();
        $keys = \Schema::getColumnListing('roles');
        $params = $this->toArray();
        $role = array();

        foreach($keys as $key) {
            if (isset($params[$key]))
                $role[$key] = $params[$key];
        }
        return json_encode($role);
    }


    public function  get_full_name(){
        $p = is_null($this->person_id)? null:Person::findOrFail($this->person_id);
        return !is_null($p)? $p->person_name . ' '. $p->father_lastname . ' '. $p->mother_lastname: '';
   }

    //----------relations-----------

    public function roles()
    {
        return $this->belongsToMany(Role::class,'user_role')->withTimestamps();
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'id_user_to');
    }

}
