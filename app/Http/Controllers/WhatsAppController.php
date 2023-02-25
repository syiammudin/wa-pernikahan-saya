<?php

namespace App\Http\Controllers;

use App\Models\PhoneBook;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function connect()
    {
        $find = Http::get(env('URL_WA_SERVER') . '/sessions/status/' . Auth::user()->name);
        $cek = json_decode($find->getBody());
        if ($cek->success && isset($cek->data->status)) {
            if ($cek->data->status == 'authenticated') {
                $image = asset('image/connect.png');
            } else {
                $find = Http::delete(env('URL_WA_SERVER') . '/sessions/delete/' . Auth::user()->name);
                $image = $this->getQR();
            }
        } else {
            $image = $this->getQR();
        }

        $data = [];
        $data['message'] = $cek->message;
        $data['image'] = $image;

        return $data;
    }
    public function getQR()
    {
        $response = Http::post(env('URL_WA_SERVER') . '/sessions/add', ['id' => Auth::user()->name, 'isLegacy' => 'false']);
        $res = json_decode($response->getBody());
        $image = $res->data->qr;

        return $image;
    }

    public function sendWa(Request $request)
    {

        $phonebox = PhoneBook::where('user_id', auth()->user()->id)->get();
        $data = [];

        foreach ($phonebox as $key => $value) {
            $cek_number = substr($value->phone, 0, 1);
            if ($cek_number == "0") {
                $number = '62' . substr($value->phone, 1);
            } else {
                $number = $value->phone;
            }

            $nameUrl = str_replace(' ', '%20', $value->name);
            $text = str_replace(['<<name>>', '<<name_url>>'], [$value->name, $nameUrl], $request->text);

            $body = [
                'image' => ['url' => 'https://indoinvite.com/nikah/upload/10411/1673027674foto_berdua.png'],
                'caption' => $text
            ];

            $response = Http::post(env('URL_WA_SERVER') . '/chats/send?id=' . auth()->user()->name, [
                'receiver' => "$number",
                'message' => $body
            ]);

            $res = json_decode($response->getBody());
            $data[] = ['status' => $res->success, 'name' => $value->name, 'number' => $number];
        }

        return ['message' => 'Success', 'data' => $data];
    }
}
