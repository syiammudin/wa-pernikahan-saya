<?php

namespace App\Http\Controllers;

use App\Models\logMessage;
use Illuminate\Http\Request;

class logMessageController extends Controller
{
    public function index(Request $request)
    {
        $log = logMessage::when($request->keyword, function ($q) use ($request) {
            return $q->where(function ($qq) use ($request) {
                return $qq->where('message', 'ILIKE', '%' . $request->keyword . '%')
                    ->orWhere('nama', 'ILIKE', '%' . $request->keyword . '%')
                    ->orWhere('nomor', 'ILIKE', '%' . $request->keyword . '%');
            });
        })
            ->when($request->status, function ($q) use ($request) {
                return $q->whereIn('status', $request->status);
            })
            ->orderBy($request->sort ?: 'id', $request->order == 'descending' ? 'desc' : 'asc');

        return $request->list == 'true' ?  $log->get() : $log->paginate($request->pageSize);
    }
}
