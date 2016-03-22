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

    public function notify(Request $request){

        //List ID from .env
        $listId = env('MAILCHIMP_LIST_ID');

        //Mailchimp instantiation with Key
        $mailchimp = new \Mailchimp(env('MAILCHIMP_KEY'));

        //Create a Campaign $mailchimp->campaigns->create($type, $options, $content)
        $campaign = $mailchimp->campaigns->create('regular', [
            'list_id' => $listId,
            'subject' => 'New Article from Scotch',
            'from_email' => 'pub@gmail.com',
            'from_name' => 'Scotch Pub',
            'to_name' => 'Scotch Subscriber'

        ], [
            'html' => $request->input('content'),
            'text' => strip_tags($request->input('content'))
        ]);

        //Send campaign
        $mailchimp->campaigns->send($campaign['id']);

        return response()->json(['status' => 'Success']);
    }
}
