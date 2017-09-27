<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    protected $redirectAfterLogout = '/admin/login';

    public function showLoginForm()
    {
        return view('admin.login.index');
    }

    protected function guard()
    {
        return auth()->guard('admin');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect($this->redirectAfterLogout);
    }
}
