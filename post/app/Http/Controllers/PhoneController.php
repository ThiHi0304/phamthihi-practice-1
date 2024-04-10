<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Phone;

class PhoneController extends Controller
{
    public function index()
    {
        $phones = Phone::all();
        return response()->json($phones);
    }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $phone = Phone::create($request->all());
        return response()->json($phone, 201);
    }

    public function show($id)
    {
        $phone = Phone::findOrFail($id);
        return response()->json($phone);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'number' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        $phone = Phone::findOrFail($id);
        $phone->update($request->all());
        return response()->json($phone, 200);
    }

    public function destroy($id)
    {
        $phone = Phone::findOrFail($id);
        $phone->delete();
        return response()->json(null, 204);
    }
}
