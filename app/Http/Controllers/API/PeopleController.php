<?php

namespace App\Http\Controllers\API;

use App\People;
use DOMDocument;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $people = People::all();
//        foreach ($people as $man) {
//            $man->phone = unserialize($man->phone);
//            $man->email = unserialize($man->email);
//            $man->ip_phone = unserialize($man->ip_phone);
//            $man->fax = unserialize($man->fax);
//        }
//        return $people;
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
            'fio' => 'required|string',
            'position' => 'required|string',
            'email.*' => 'email'
        ]);

        //'fio', 'position', 'phone', 'ip_phone', 'building_id', 'group_id', 'room', 'room_type', 'email'


        return People::create([
            'fio' => $request['fio'],
            'position' => $request['position'],
            'status' => $request['status'],
            'priority' => $request['priority'],
            'phone' => serialize($request['phone']),
            'ip_phone' => serialize($request['ip_phone']),
            'building_id' => $request['building_id'],
            'group_id' => $request['group_id'],
            'room' => $request['room'],
            'room_type' => $request['room_type'],
            'email' => serialize($request['email']),
            'fax' => serialize($request['fax'])
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
            'fio' => 'required|string',
            'position' => 'required|string',
            'email.*' => 'email'
        ]);

        //'fio', 'position', 'phone', 'ip_phone', 'building_id', 'group_id', 'room', 'room_type', 'email'

        $data = ([
            'fio' => $request['fio'],
            'status' => $request['status'],
            'position' => $request['position'],
            'priority' => $request['priority'],
            'phone' => serialize($request['phone']),
            'ip_phone' => serialize($request['ip_phone']),
            'building_id' => $request['building_id'],
            'group_id' => $request['group_id'],
            'room' => $request['room'],
            'room_type' => $request['room_type'],
            'email' => serialize($request['email']),
            'fax' => serialize($request['fax']),
        ]);

        $man = People::findOrFail($id);
        $man->update($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $man = People::findOrFail($id);
        $man->delete();
        return ['message' => 'Group deleted!'];
    }

    public function search(Request $request)
    {
        $q = $request['q'];
        $people = People::whereHas('group', function ($query) use ($q) {
            $query->where('name', 'like', "%$q%");
        })
            ->orwhere('fio', 'LIKE', "%$q%")
            ->orwhere('position', 'LIKE', "%$q%")
            ->orwhere('phone', 'LIKE', "%$q%")
            ->orwhere('fax', 'LIKE', "%$q%")
            ->orwhere('ip_phone', 'LIKE', "%$q%")
            ->orderBy('fio')
            ->get();
        foreach ($people as $person) {
            $person->phone = unserialize($person->phone);
            $person->fax = unserialize($person->fax);
            $person->email = unserialize($person->email);
            $person->ip_phone = unserialize($person->ip_phone);
            $person->group_name = $person->group->name;
            $person->building_name = $person->building->name;
            $person->building_address = $person->building->address;
        }
        return $people;

    }

    public function exportToXml()
    {
        $people = People::all();
        $dom = new domDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
        $root = $dom->createElement("AddressBook"); // Создаём корневой элемент
        $dom->appendChild($root);
        $logins = array("User1", "User2", "User3"); // Логины пользователей
        $passwords = array("Pass1", "Pass2", "Pass3"); // Пароли пользователей
        foreach ($people as $person) {
            $user = $dom->createElement("Contact"); // Создаём узел "user"
            $fio = explode(' ', $person->fio);
            $lastName = $dom->createElement('LastName', $fio[0]);
            $firstName = $dom->createElement('FirstName', $fio[1]);
            $user->appendChild($lastName);
            $user->appendChild($firstName);
            $phone = $dom->createElement("Phone"); // Создаём узел "phone"
            $phonenumber = $dom->createElement("phonenumber", unserialize($person->ip_phone)[0]); // Создаём узел "phone"
            $accountindex = $dom->createElement("accountindex", '1'); // Создаём узел "phone"
            $downloaded = $dom->createElement("downloaded", '0'); // Создаём узел "phone"
            $phone->appendChild($phonenumber);
            $phone->appendChild($accountindex);
            $phone->appendChild($downloaded);
            $user->appendChild($phone);
            $root->appendChild($user);

        }
        $dom->save("users.xml"); // Сохраняем полученный XML-документ в файл
    }
}
