<?php

namespace App\Http\Controllers\API;

use App\Group;
use App\People;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;


class NoteController extends Controller
{

    public function get_all_groups()
    {
        $groups = Group::orderBy('priority', 'DESC')->get();
        $data = [];
        foreach ($groups as $group) {
            if ($group->children->count() > 0) {
                $childrenData = $this->add_to_data($group->children);
                foreach ($childrenData as $child) {
                    array_push($data, $child);
                }
            }
        }

        return $data;
    }

    public function show(Request $request, $id)
    {
        $group = Group::find($id);
        if ($group->children->count() > 0) {
            return json_encode($this->add_to_data($group->children));
        } else {
            $data = [];
            $people = $group->people->sortBy(function ($item) {
                return $item->status . '-' . $item->fio;
            });
            if ($people->count() > 0) {
                foreach ($people as $man) {
                    $building = $man->building;
                    $data[] = [
                        'id' => $group->id,
                        'group_id' => $man->group_id,
                        'group_info' => $group->name,
                        'group_tel' => unserialize($group->phone),
                        'group_mail' => unserialize($group->email),
                        'name' => $man->fio,
                        'phone' => unserialize($man->phone),
                        'ip_phone' => unserialize($man->ip_phone),
                        'fax' => unserialize($man->fax),
                        'email' => unserialize($man->email),
                        'position' => $man->position,
                        'building' => $building->name . ", " . $man->room,
                        'room_type' => $man->room_type,
                        'address' => $building->address,
                    ];
                }
            }
            return json_encode($data);
        }
    }

    public function add_to_data($groups)
    {
        $data = [];
        foreach ($groups as $group) {
            $people = $group->people->sortBy(function ($item) {
                return $item->status . '-' . $item->fio;
            });
            if ($people->count() > 0) {
                foreach ($people as $man) {
                    $building = $man->building;
                    $data[] = [
                        'id' => $group->id,
                        'priority' => $group->priority,
                        'parent_priority' => $group->parent->priority,
                        'group_id' => $man->group_id,
                        'group_info' => $group->name,
                        'group_tel' => unserialize($group->phone),
                        'group_mail' => unserialize($group->email),
                        'name' => $man->fio,
                        'phone' => unserialize($man->phone),
                        'ip_phone' => unserialize($man->ip_phone),
                        'fax' => unserialize($man->fax),
                        'email' => unserialize($man->email),
                        'position' => $man->position,
                        'building' => $building->name,
                        'room' => $man->room,
                        'room_type' => $man->room_type,
                        'address' => $building->address,
                    ];
                }
            }

        };
        return $data;
    }

    public function index()
    {
        $groups = Group::orderBy('priority', 'DESC')->get();
        $data = [];
        foreach ($groups as $group) {
            $group_data = [];
            if ($group->children->count() > 0) {
                foreach ($group->children as $child) {
                    $child->phone = unserialize($child->phone);
                    $child->email = unserialize($child->email);
                    $child->ip_phone = unserialize($child->ip_phone);
                    $child->priority = $child->priority;
                }
                $group_data = ['group_name' => $group->name, 'id' => $group->id, 'priority' => $group->priority, 'children' => $group->children];
            }
            if ($group_data) {
                $data[] = $group_data;
            }
        }
        return json_encode($data);
    }
}
