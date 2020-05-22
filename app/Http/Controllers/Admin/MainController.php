<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Image;
use App\Models\Info;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Order;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class MainController extends Controller
{
    public function viewSignIn()
    {
        return view('admin.sign_in');
    }

    public function signIn(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        $admin = Admin::where('username', $request['username'])->where('password', $request['password'])->with('role')->first();
        if (isset($admin)) {
            session()->put('vK68TF23TfYKYDBZSCC9', 1);
            session()->put('admin', $admin);
            session()->save();
            return redirect()->route('viewIndex');
        } else {
            return back()->withErrors('Неправильный пароль или логин');
        }
    }

    public function signOut(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();
        return redirect()->route('viewSignIn');
    }

    public function viewIndex()
    {
        $count = (object)[];
        $count->items = Item::count();
        $count->orders = Order::count();
        $count->users = User::count();
        $count->managers = Admin::count();

        $today = Carbon::now()->format('Y-m-d');
        $week = Carbon::today()->subWeek()->format('Y-m-d');
        $month = Carbon::today()->subMonth()->format('Y-m-d');

        $users = (object)[];
        $users->today = User::whereDate('created_at', $today)->count();
        $users->week = User::whereDate('created_at', '>', $week)->count();
        $users->month = User::whereDate('created_at', '>', $month)->count();
        $users->all = $count->users;

        $items = (object)[];
        $items->today = Item::whereDate('created_at', $today)->count();
        $items->week = Item::whereDate('created_at', '>', $week)->count();
        $items->month = Item::whereDate('created_at', '>', $month)->count();
        $items->all = $count->items;

        $orders = (object)[];
        $orders->today = Order::whereDate('created_at', $today)->count();
        $orders->week = Order::whereDate('created_at', '>', $week)->count();
        $orders->month = Order::whereDate('created_at', '>', $month)->count();
        $orders->all = $count->orders;

        return view('admin.index', compact('users', 'items', 'orders', 'count'));
    }

    public function viewInfo()
    {
        return view('admin.other.info', ['info' => Info::all()]);
    }

    public function saveInfo(Request $request)
    {
        if (!$request->currentAdmin->role->general >= Role::ACCESS_UPDATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $payment = Info::where('key', 'payment')->first();
        $payment->description = $request['payment'];
        $payment->save();
        $shipping = Info::where('key', 'shipping')->first();
        $shipping->description = $request['shipping'];
        $shipping->save();
        return back()->with('message', 'Успешно сохранено');
    }

    public function storeImage(Request $request)
    {
        $rules = [
            'image' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:16384'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        $image = Image::create([
            'path' => $this->upload($request['image'])
        ]);

        $data['statusCode'] = 200;
        $data['message'] = 'Success';
        $data['result'] = $image->id;

        return response()->json($data, $data['statusCode']);
    }

    public function destroyImage(Request $request){
        $rules = [
            'id' => 'required|exists:images,id'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors();
            $data['result'] = null;
            return response()->json($data, $data['statusCode']);
        }

        $image = Image::find($request['id']);
        $this->deleteFile($image->path);

        ItemImage::where('image_id', $image->id)->delete();
        $image->delete();

        $data['statusCode'] = 200;
        $data['message'] = 'Success';
        $data['result'] = null;

        return response()->json($data, $data['statusCode']);

    }

    public static function getText($sum){
        if ($sum == 0) {
            return 'Никаких привилегии';
        }
        if ($sum <= \App\Models\Role::ACCESS_READ){
            return 'Читать';
        } else if ($sum <= \App\Models\Role::ACCESS_CREATE) {
            return 'Читать, Создать';
        } else if ($sum <= \App\Models\Role::ACCESS_UPDATE) {
            return 'Читать, Создать, Редактировать';
        } else {
            return 'Читать, Создать, Редактировать, Удалить';
        }
    }
}
