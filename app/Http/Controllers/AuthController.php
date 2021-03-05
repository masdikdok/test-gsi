<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RedirectsUsers;

use App\Models\Login;
use App\Models\Karyawan;
use App\Components\Helpers;

class AuthController extends Controller
{

    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    protected function loginFailed($errors){
        // error
        return view('auth.login')
            ->withErrors($errors);
    }

    public function login(Request $request){
        if($request->isMethod('post')){
            $this->validate($request, [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $karyawan = Karyawan::where('npk', $request->username)->first();
            if ($karyawan) {
                $login = Login::whereRaw('password = MD5("'. $request->password .'")')
                    ->where('npk', $request->username)
                    ->orderBy('created_at', 'DESC')
                    ->get();

                if ($login->count() == 1) {
                    $request->session()->put('credentials', $karyawan);

                    return redirect()->route('admin.dashboard');
                }elseif ($login->count() > 1) {
                    // error
                    return $this->loginFailed([
                        'username' => 'Password has been updated since ' . \Carbon\Carbon::parse($login->last()->created_at)->diffForHumans()
                    ]);
                }else{
                    // error
                    return $this->loginFailed([
                        'username' => 'Password invalid!'
                    ]);
                }
            }else{
                return $this->loginFailed([
                    'username' => 'Username invalid!'
                ]);
            }
        }

        return view('auth.login');
    }

    public function logout(Request $request){
        $nama = $request->session()->get('credentials')->nama;
        $request->session()->invalidate();

        Helpers::setAlert([
            'type' => 'info',
            'message' => 'See you later ' . $nama
        ]);

        return redirect('/');
    }

}
