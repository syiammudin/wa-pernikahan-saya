<?php

namespace App\Http\Controllers;

use App\Models\PhoneBook;
use Illuminate\Http\Request;

class PhoneBoookController extends Controller
{
    public function index()
    {
        return PhoneBook::where('user_id', auth()->user()->id)->get();
    }

    public function store(Request $request)
    {

        $request['user_id'] = auth()->user()->id;
        $data = PhoneBook::create($request->all());

        return ['message' => 'Simpan success'];
    }

    public function show($id)
    {
        return PhoneBook::find($id);
    }

    public function update(Request $request, $id)
    {
        $phoneBook = PhoneBook::find($id);
        $phoneBook->update($request->all());

        return ['message' => 'Update success', 'data' => $phoneBook];
    }

    public function destroy($id)
    {
        $phoneBook = PhoneBook::find($id);
        $phoneBook->delete();

        return ['message' => 'delete success'];
    }
}
