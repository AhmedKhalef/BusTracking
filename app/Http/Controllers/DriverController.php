<?php

namespace App\Http\Controllers;

use App\Bus;
use App\Role;
use App\User;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    //
    public function index()
    {
    	$driver = Role::find(2);
    	$drivers = $driver->users;

    	return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        $buses = Bus::all();
    	return view('drivers.create', compact('buses'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'bus' => 'nullable|numeric'
        ]);

        $bus = Bus::find($request->bus);
        
        $role = Role::find(2);

        $driver = new User();

        $driver->name = $request->name;
        $driver->phone =  $request->phone;
        $driver->password = bcrypt($request->password);

        if (isset($bus)) 
        {
            $driver->associate($bus);
        }

        $driver->save();

        $driver->attachRole($role);

         $notification = [
            'type' => 'success',
            'message' => 'Driver is added successfully!',
            'title' => 'Created'
        ];

        return Redirect::to('/drivers')->with([
            'type' => $notification['type'],
            'title' => $notification['title'],
            'message' => $notification['message']
        ]);
    }
}
