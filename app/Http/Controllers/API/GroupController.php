<?php

namespace App\Http\Controllers\API;

use App\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        switch ($request['q']) {
//            case 'parents':
//                $groups = Group::where('parent_id', null)->get();
//                break;
//
//            case 'children':
//                $groups = Group::whereNotNull('parent_id')->where('id', Auth::id())->get();
//                break;
//
//            case 'all':
//                $groups = Group::all();
//                break;
//        }
//
//        foreach ($groups as $group) {
//            $group->phone = unserialize($group->phone);
//            $group->email = unserialize($group->email);
//        }
//        return auth()->user()->id;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //'name', 'parent_id', 'priority', 'email', 'phone'

        $this->validate($request, [
            'name' => 'required|string',
            'parent_id' => 'min:0',
            'priority' => 'required|integer',
            'email.*' => 'email'
        ]);

        return Group::create([
            'level' => ($request['parent_id'] ? Group::find($request['parent_id'])->level + 1 : 0),
            'name' => $request['name'],
            'parent_id' => $request['parent_id'],
            'priority' => $request['priority'],
            'phone' => serialize($request['phone']),
            'email' => serialize($request['email']),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
        $this->validate($request, [
            'name' => 'required|string',
            'parent_id' => 'min:0',
            'priority' => 'required|integer',
            'email.*' => 'email'
        ]);

        $data = ([
            'level' => ($request['parent_id'] ? Group::find($request['parent_id'])->level + 1 : 0),
            'name' => $request['name'],
            'parent_id' => $request['parent_id'],
            'priority' => $request['priority'],
            'phone' => serialize($request['phone']),
            'email' => serialize($request['email'])
        ]);
        $group = Group::findOrFail($id);
        $group->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        $group->delete();
        return ['message' => 'Group deleted!'];
    }
}
