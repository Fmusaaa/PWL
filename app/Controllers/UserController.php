<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class UserController extends BaseController
{
    public function profile()
    {
        $data = [
            'username'  => session()->get('username')  ?? '-',
            'nama'      => session()->get('nama')       ?? session()->get('username') ?? '-',
            'role'      => session()->get('role')       ?? '-',
            'email'     => session()->get('email')      ?? '-',
            'loginTime' => session()->get('loginTime')  ?? '-',
            'isLoggedIn'=> session()->get('isLoggedIn') ?? false,
        ];

        return view('v_profile', $data);
    }
}
