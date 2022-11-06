<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('home',compact('users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'             => 'required|email|unique:users,email',
            'full_name'         => 'required|string',
            'date_of_joining'   => 'required|date|date_format:d/m/Y|before_or_equal:today',
            'date_of_leaving'   => 'required_without:still_working|nullable|date|date_format:d/m/Y|after:date_of_joining|before:today',
            'still_working'     => 'required_without:date_of_leaving|boolean',
            'image'             => 'required|image',
        ], []);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->getMessageBag()->toArray()], 422);
        }

        $imageName = time() . Str::uuid() . '.' . $request->image->extension();
        $request->image->move(public_path('uploads'), $imageName);

        User::create([
            'email'             => $request->email,
            'full_name'         => $request->full_name,
            'date_of_joining'   => $request->date_of_joining,
            'date_of_leaving'   => $request->date_of_leaving,
            'still_working'     => $request->still_working,
            'image'             => $imageName,
        ]);

        return response()->json(['message' => 'Success !']);
    }

    public function delete(Request $request)
    {
        User::destroy($request->id);
        return response()->json(['message' => 'Success !']);
    }
}
