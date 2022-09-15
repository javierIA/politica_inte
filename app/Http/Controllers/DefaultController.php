<?php

namespace App\Http\Controllers;

use App\Address;
use App\Area;
use App\CartItem;
use App\Cart;
use App\Communication;
use App\FedDistrict;
use App\FedEntity;
use App\LocDistrict;
use App\MailItem;
use App\Municipality;
use App\Ocupation;
use App\Person;
use App\PhoneCode;
use App\Producto;
use App\Section;
use App\SocialNetwork;
use App\User;
use App\WishItem;
use App\WishList;
use App\Zone;
use Illuminate\Http\Request;
use App\Currency;
use App\Categoria;
use App\CustomerService;
use App\Country;
use App\Http\Controllers\Lang;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackMail;

/*include_once "./library/jpgraph-4.3.1/src/jpgraph.php";
include_once "./library/jpgraph-4.3.1/src/jpgraph_line.php";
include_once "./library/jpgraph-4.3.1/src/jpgraph_bar.php";
include_once "./library/jpgraph-4.3.1/src/jpgraph_date.php";
include_once "./library/jpgraph-4.3.1/src/jpgraph_scatter.php";

include_once "./library/TCPDF/tcpdf.php";*/

class DefaultController extends Controller
{
    public function dashboard()
    {
        $link = 'home';
        $fed_district = FedDistrict::all()->count();
        $loc_districts = LocDistrict::all()->count();
        $areas = Area::all()->count();
        $zones = Zone::all()->count();
        return view('user.political.main', compact('link', 'fed_district', 'loc_districts', 'areas', 'zones'));
    }

    public function logout(){
        return redirect(route('dashboard'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * $id
     * @return \Illuminate\Http\Response
     */
    public function verify($temp_id)
    {
        $user = User::where('temp_address',$temp_id)->first();
        if(is_null($user))
            return redirect(route('dashboard'));
        $person = $user->person;
        $person->birth_date = date_format(date_create($person->birth_date),"d/m/Y");
        $person->person_sex = $person->person_sex == 'f'? trans('admin.female'): trans('admin.male');
        $phone_codes = PhoneCode::all();
        $cellphone = Communication::where('person_id', $person->id)->where('type','cellphone')->first();

        return view('user.political.verify', compact('person', 'user', 'phone_codes', 'cellphone'));
    }

    public function saveVerify(Request $request, $id)
    {
        $items = $request->all();
        $person = Person::findOrFail($id);
        if(!is_null($person)){
            $items['password'] = bcrypt($items['password']); //establecer la contrase;a
            //$items['validity'] = '1111';
            $items['temp_address'] = null;
            $person->fill($items);
            $users = $person->users;
            foreach ($users as $u) {
                $u->fill($items);
                $u->update();
            }
            $cellphone = Communication::where('person_id', $person->id)->where('type','cellphone')->first();
            $cellphone->fill($items);
            $person->update();
            $cellphone->update();
            return json_encode(['status'=> true]);
        }
        return json_encode(['status'=> false]);
    }

    public function my_account()
    {
        return view('user.my_account');
    }

    public function details($id)
    {
        $product = Producto::where('id', $id)->first();
        if (is_null($product))
            return redirect()->back();

        return view('user.details', compact('product'));
    }

    public function register()
    {
        return view('user.register');
    }

    public function about_us()
    {
        $link = 'about';
        return view('user.cleaningcompany.about', compact('link'));
    }

    public function mission()
    {
        $link = 'mission';
        return view('user.cleaningcompany.mission', compact('link'));
    }

    public function services()
    {
        $link = 'services';
        return view('user.cleaningcompany.services', compact('link'));
    }

    public function contact_us()
    {
        $link = 'contact';
        return view('user.cleaningcompany.contact', compact('link'));
    }

    public function sendmail(Request $request)
    {
        if($request->get('subject') !== 'qwerty') {
            $this->statisticsRequest($request);
            $this->saveMailLog($request);
        }

        $toEmail = array();
        $customer_services = CustomerService::all();
        foreach ($customer_services as $cs)
            $toEmail[] = $cs->email;

        Mail::to($toEmail)->send(new FeedbackMail($request, $request->get('subject')));

        if (Mail::failures())
            return response()->json(['success' => false]);
        return response()->json(['success' => true]);
    }

    private function saveMailLog(Request $request)
    {
        $text = '---------------'.date("Y/m/d h:i a")."---------------\n";
        $text .= 'Client name: '. $request->get('name')."\n";
        $text .= 'Client email: '. $request->get('email')."\n";
        $text .= 'Comments: '. $request->get('msg')."\n\n";
        file_put_contents(storage_path().'/logs/mail.log', $text, FILE_APPEND | LOCK_EX);
    }

}
