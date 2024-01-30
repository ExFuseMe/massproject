<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(){
        $applications = Application::all();
        return response()->json($applications);
    }
    public function store(StoreApplicationRequest $request){
        $request->validated();
        Application::create([
            "name" => $request->name,
            "email" => $request->email,
        ]);
        return response()->json(["status"=>200, "text"=>"Request was created successfully"]);
    }
}
