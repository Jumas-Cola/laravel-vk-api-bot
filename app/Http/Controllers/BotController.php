<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BotController extends Controller
{
    public function handle(Request $request)
    {
        switch ( $request->type ) {
            case 'confirmation':
                return;
            default:
                return response()->json(['message' => 'ok']);
        }
    }
}
