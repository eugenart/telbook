<?php

namespace App\Http\Controllers\API;

use App\Emergency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmergencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $em = Emergency::orderBy('name')->get();
        foreach ($em as $e) {
            $e->phone = unserialize($e->phone);
            $e->ip_phone = unserialize($e->ip_phone);
        }
        return $em;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Emergency::create([
            'name' => $request['name'],
            'phone' => serialize($request['phone']),
            'ip_phone' => serialize($request['ip_phone']),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = ([
            'name' => $request['name'],
            'phone' => serialize($request['phone']),
            'ip_phone' => serialize($request['ip_phone']),
        ]);

        $em = Emergency::findOrFail($id);
        $em->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $em = Emergency::findOrFail($id);
        $em->delete();
        return ['message' => 'Em deleted!'];
    }
}
