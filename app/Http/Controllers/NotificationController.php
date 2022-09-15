<?php

namespace App\Http\Controllers;

use App\Exports\ClassExport;
use App\Notification;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NotificationController extends Controller
{
    public static $methods = [
        'exportData' => ['exportData'],
        'importData' => ['importData']
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

        return Excel::download(new ClassExport(Notification::class,$items),'Notification.'.$format);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = trans('admin.notification');
        $columns = Notification::getTableColumns();
        return view('admin.notification.index', compact('title', 'columns'));
    }

    /**
     * Filter the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request){
        $data = $request->all();
        $exceptions = ['_token' => ''];
        $selects = ["(SELECT name FROM users WHERE id = id_user_to) as user_to",
            "(SELECT name FROM users WHERE id = id_user_from) as user_from",
            "to_char(T.created_at, 'HH12:MI AM') as time",
            "to_char(T.created_at, 'dd/MM/YYYY') as date",
            "CASE WHEN type = 1 THEN '".trans('admin.high')."' WHEN type = 2 THEN '".trans('admin.medium')."' WHEN type = 3 THEN '".trans('admin.low')."' END as importance",
            "null as selected"
        ];
        $info = [
            'columns' =>  ['selected' ,'id', 'id_user_to', 'user_to', 'id_user_from', 'user_from', 'message', 'type', 'showed', 'acepted_time','time','date', 'importance'],
            'controller' => 'NotificationController',
            'order_by' => 'T.type',
            'table' => with(new Notification())->getTable(),
            'route' => [
                'script_function1' =>[
                    'text' => trans('admin.show'),
                    'function' => 'seeNotification',
                    'modal' => 'ModalNotification',
                    'icon' => 'entypo-eye',
                    'data' => 'id'
                ],
                'script_function' =>[
                    'text' => trans('admin.accept_notification'),
                    'function' => 'acceptNotification',
                    'icon' => 'entypo-check',
                    'data' => 'id'
                ],
                'destroy' => [
                    'name' => 'notification.destroy',
                    'text' => trans('admin.delete_notification')
                ]
            ]
        ];
        return $this->internalFilter($data, $info ,$exceptions, [], $selects );
    }

    /**
     * Accept notification.
     *
     * @param $id
     * @return void
     */
    public function acceptNotification($id){
        try {
            $notification = Notification::findOrFail($id);
            if($notification->acepted_time != null)
                return json_encode(trans('admin.notification_already_accepted'));
            $date = new \DateTime('NOW');
            $notification->acepted_time = $date;
            $this->savelog($notification, 'update');
            $notification->update();
            return json_encode(true);
        }
        catch(\Exception $e){
            return json_encode(false);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Notification $notification
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Notification $notification)
    {
        $this->savelog($notification,'delete');
        $notification->delete();
        return redirect(route('notification.index'));
    }
}
