<?php

namespace App\Http\Controllers;

use App\People;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetStaffController extends Controller
{
    public function index()
    {
        if (Auth::user()->role) {
            $people = People::orderBy('fio')->paginate(10);
        } else {
            $people = People::where('group_id', Auth::user()->group_list)->orderBy('fio')->paginate(10);
        }
        foreach ($people as $man) {
            $man->phone = unserialize($man->phone);
            $man->email = unserialize($man->email);
            $man->ip_phone = unserialize($man->ip_phone);
            $man->fax = unserialize($man->fax);
            $man->type_id = $man->building->group_id;
        }
        return $people;
    }

    public function show(Request $request)
    {
        $q = $request['q'];
        if (Auth::user()->role) {
            $people = People::orderBy('fio')->where('fio', 'like', "%$q%")->paginate(10);
        } else {
            $people = People::where('group_id', Auth::user()->group_list)->where('fio', 'like', "%$q%")->orderBy('fio')->paginate(10);
        }
        foreach ($people as $man) {
            $man->phone = unserialize($man->phone);
            $man->email = unserialize($man->email);
            $man->ip_phone = unserialize($man->ip_phone);
            $man->fax = unserialize($man->fax);
        }
        return $people;
    }
}
