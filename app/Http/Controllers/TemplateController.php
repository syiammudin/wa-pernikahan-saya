<?php

namespace App\Http\Controllers;

use App\Models\TemplateMessage;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        return TemplateMessage::get();
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
}
