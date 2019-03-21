<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use mysql_xdevapi\Collection;

class GetGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        switch ($request['q']) {
            case 'parents':
                $groups = Group::where('parent_id', null)->get();
                break;

            case 'children':
                if (Auth::user()->role) {
                    $groups = Group::whereNotNull('parent_id')->get();
                } else {
                    $groups = Group::whereNotNull('parent_id')->where('id', Auth::user()->group_list)->get();
                }
                break;

            case 'all':
                $groups = Group::all();
                $groups = $groups->sortBy(function ($item) {
                    return $item->parent_id ? $item->parent->name . '-' . $item->name : $item->name;
                })->values();
                break;
            default:
                $groups = Group::all();
                $groups = $groups->sortBy(function ($item) {
                    return $item->parent_id ? $item->parent->name . '-' . $item->name : $item->name;
                })->values();
                //$groups = $groups->chunk(4);
               // $groups = $groups->toArray();
        }

        foreach ($groups as $group) {
            $group->phone = unserialize($group->phone);
            $group->email = unserialize($group->email);
        }
        return $groups;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $q = $request['q'];
        $groups = Group::where('name', 'like', "%$q%")->get();
        $groups = $groups->sortBy('name')->values()->all();
        foreach ($groups as $group) {
            $group->phone = unserialize($group->phone);
            $group->email = unserialize($group->email);
        }
        return $groups;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
