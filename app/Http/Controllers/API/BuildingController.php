<?php

namespace App\Http\Controllers\API;

use App\BuildGroup;
use App\Building;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buildings = Building::all();
        foreach ($buildings as $building) {
            $building->phone = unserialize($building->phone);
            $building->group_name = $building->group->name;
            $building->group_id = $building->group->id;
        }
        return $buildings->groupBy('group_id')->sortBy('position');
    }

    public function get_all_group_ids() {
        $b_grs = BuildGroup::all();
        return $b_grs;
    }

    public function get_choosen_builds($id) {
        $buildings = Building::where('group_id', $id)->orderBy('position')->get();
        return $buildings;
    }

    public function get_all_buildings() {
        $buildings = Building::orderBy('name')->get();
        return $buildings;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'address' => 'required|string'
        ]);
        $last_building = Building::where('group_id', $request['group_id'])->orderBy('position', 'desc')->first();
        $last_position = $last_building ? $last_building->position + 1 : 1;
        return Building::create([
            'name' => $request['name'],
            'address' => $request['address'],
            'position' => $last_position,
            'group_id' => $request['group_id'],
            'phone' => serialize($request['phone']),
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
            'address' => 'string'
        ]);
        $data = ([
            'name' => $request['name'],
            'address' => $request['address'],
            'phone' => serialize($request['phone']),
        ]);
        $building = Building::findOrFail($id);
        $building->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $building = Building::findOrFail($id);
        $building->delete();
        return ['message' => 'Building deleted!'];
    }

    public function up_down(Request $request, $id)
    {
        $building = Building::findOrFail($id);
        if ($request['method'] == 'up') {
            Building::where('group_id', $building->group_id)->where('position', $building->position - 1)->update(['position' => $building->position]);
            $building->update(['position' => $building->position - 1]);
        } else {
            Building::where('group_id', $building->group_id)->where('position', $building->position + 1)->update(['position' => $building->position]);
            $building->update(['position' => $building->position + 1]);
        }
    }
}
