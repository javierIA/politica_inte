<?php

namespace App\Http\Middleware;

use App\SystemFunction;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Role;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //verifico los permisos
        $action = $request->route()->getAction();
        list($controller, $action) = explode('@', class_basename($action['controller']));
        View::share('current_controller', $controller);
        View::share('current_action', $action);
        if($controller != 'HomeController'){
            if( !$this->checkRole($request->user(),$controller)/* || !$request->user()->get_Permission($controller,$action)*/)
                return redirect()->route('home');
        }
        return $next($request);
    }

    private function checkRole($user, $controller)
    {
        $sfc = SystemFunction::where('system_function_name',$controller)->first();

        if(is_null($sfc) || empty($sfc))
            return false;

        $controller_roles = $sfc->roles()->get();
        $roles = array();
        foreach($controller_roles as $cr)
            $roles[] = $cr->name;
        return $user->hasAnyRole($roles);
    }
}
