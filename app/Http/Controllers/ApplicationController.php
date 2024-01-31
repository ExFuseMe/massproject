<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerApplicationRequest;
use App\Http\Requests\StoreApplicationRequest;
use App\Mail\ApplicationAnswering;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;

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
            "message" => $request->message
        ]);
        return response()->json(["status"=>200, "text"=>"Request was created successfully"]);
    }
    public function update(AnswerApplicationRequest $request, $id){
        $request->validated();
        //finding an application by id
        $application = Application::findOrFail($id);
        $application->comment = $request->comment;
        //change status to 'resolved'
        $application->status = "resolved";
        $application->save();
        //send mail to user
        Mail::send("mail.request.answer", ["comment" => $request->comment], function($message) use($application){
            $message->to($application->email, $application->name)->subject("Ответ на заявку");
        });
        return response()->json(["status" => 200, "text" => "Succesfully answered on request. Email was sent to user"]);
    }
}
