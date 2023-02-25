<?php

namespace App\Http\Controllers;

use App\Models\TemplateMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index()
    {
        return TemplateMessage::where('user_id', auth()->user()->id)->get();
    }

    public function store(Request $request)
    {
        $request['user_id'] = auth()->user()->id;
        $templateMessage = TemplateMessage::create($request->all());

        return ['message' => 'data has been save'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TemplateMessage $templateMessage)
    {
        return $templateMessage;
    }

    public function update(Request $request, TemplateMessage $templateMessage)
    {
        $templateMessage->update($request->all());

        return ['message' => 'data has been save'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TemplateMessage $templateMessage)
    {
        $templateMessage->delete();
        return ['data has been saved'];
    }

    public function upload(Request $request)
    {
        $request->validate(['file' => 'required|mimes:jpg,jpeg,png|max:3000']);
        $path = $request->file('file')->store('public/' . date('Y/m/d'));

        $data = [
            'url' => url(Storage::url($path)),
            'name' => $request->file->getClientOriginalName(),
            'path' => $path,
            'size' => $request->file->getSize(),
            'mime' => $request->file->extension(),
        ];


        return $data;
    }
}
