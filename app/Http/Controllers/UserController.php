<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Group;
use App\Imports\ExcelImport;
use App\Person;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Currency;
use App\Categoria;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public static $methods = [
        'exportData' => ['exportData'],
        'importData' => ['importData'],
        'profile' => ['profile', 'update_profile']
    ];

    /**
     * Filter the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $format
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportData(Request $request, $format, $ids){
        $selection = json_decode($ids);
        $items = $request->all();
        unset($items['_token']);
        if (ob_get_contents()) ob_end_clean();
        ob_start(); // and this

        if(!is_null($selection))
            $items['raw'] = sprintf("id in (%s)", join(',', $selection));
        $exception = ['password', 'remember_token', 'temp_address', 'avatar'];

        return Excel::download(new ClassExport(User::class, $items, $exception),'User.'.$format);
    }


    /**
     * Filter the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $format
     * @return BinaryFileResponse
     */
    public function importData(Request $request){
        $file = $request->file;
        Excel::import(new ExcelImport(SystemFunction::class), $file);
        return redirect(route('user.index'));
    }

    public function index()
    {
        $title = trans('admin.user');
        $repo = User::all();
        $columns = User::getTableColumns();
        return view('admin.user.index', compact('title', 'repo', 'columns'));
    }

    public function create()
    {
        $title = trans('admin.add_user');
        $roles = Role::all(['id', 'name']);
        $personas = Person::all(['id', 'person_name']);
        return view('admin.user.create', compact('title', 'roles', 'personas'));
    }

    /**
     * Filter the specified resource in storage.
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request){
        $data = $request->all();
        $exceptions = ['_token' => ''];
        $selects = [
            "(SELECT person_name || ' ' || father_lastname || ' ' || mother_lastname FROM persons WHERE id = T.person_id ) as full_name",
            "null as selected"
          ];

        $info = [
            'columns' =>  ['selected' ,'id', 'name', 'full_name', 'email'],
            'controller' => 'UserController',
            'table' => with(new User())->getTable(),
            'route' => [
                'edit' => [
                    'name' => 'user.edit',
                    'text' => trans('admin.edit_user')
                ],
                'destroy' => [
                    'name' => 'user.destroy',
                    'text' => trans('admin.delete_user')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(['id', 'name']);
        $user_role = $user->roles()->first()->id;
        $personas = Person::all(['id', 'person_name']);
        $title = $user->name;
        return view('admin.user.edit', compact('title', 'user', 'roles', 'user_role', 'personas'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($request->role);
        $user = User::findOrFail($id);

        $exist = User::whereName($request->name)->count();
        if ($user->name != $request->name && $exist == 0)
            $user->name = $request->name;

        $exist = User::whereEmail($request->email)->count();
        if ($user->email != $request->email && $exist == 0)
            $user->email = $request->email;

        $user->roles()->sync($role);

        if (!empty($request->avatar)) {
            if(is_file(public_path().$user->avatar)) {
                unlink(public_path() . $user->avatar);
            }
            $user->avatar = $this->saveAvatar($request->avatar, $request->name, 0);
        }
        $user->update();
        return redirect(route('user.index'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users|min:6',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|integer',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'max:10000|mimes:jpg,jpeg,png',
        ]);

        $role = Role::where('id', $request->role)->first();

        $user = new User();
        $user->name = $request->name;
        $user->person_id = $request->person_id;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        if(isset($request->avatar)) {
            $user->avatar = $this->saveAvatar($request->avatar, $request->name, 0);
        }
        $user->save();
        $user->roles()
            ->attach($role);
        return redirect(route('user.index'));
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users|min:6',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'confirmed',
            'avatar' => 'max:10000|mimes:jpg,jpeg,png',
        ]);

        $user = \App\User::find(auth()->user()->id);

        if (!empty($request->name))
            $user->name = $request->name;
        if (!empty($request->email))
            $user->email = $request->email;
        if (!empty($request->password))
            $user->password = bcrypt($request->password);
        if (!empty($request->avatar)) {
            if(is_file(public_path().$user->avatar)) {
                unlink(public_path() . $user->avatar);
            }
            $user->avatar = $this->saveAvatar($request->avatar, $request->name, 0);
        }
        $user->update();
        //return redirect(route('user.index'));
        return redirect(route('home'));
    }

    public function profile(Request $request)
    {
        $title = trans('admin.edit_profile');
        return view('admin.user.profile', compact('title'));
        //if (Auth::user()->hasRole('admin'))
        //  return view('admin.profile');
        // return view('home');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if(is_file(public_path().$user->avatar)) {
            unlink(public_path() . $user->avatar);
        }
        User::destroy($id);
        return redirect(route('user.index'));
    }
}
