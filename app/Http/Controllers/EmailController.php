<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller {

    public function send(Request $request)
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $attach = $request->file('file');

        Mail::send('emails.send', ['title' => $title, 'content' => $content], function ($message) use ($attach)
        {

            $message->from('me@gmail.com', 'Christian Nwamba');

            $message->to('chrisn@scotch.io');

            $message->attach($attach);

            $message->subject("Hello from Scotch");

        });


        return response()->json(['message' => 'Request completed']);
    }
}
