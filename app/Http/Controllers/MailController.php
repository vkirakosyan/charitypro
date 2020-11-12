<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
class MailController extends Controller
{
    //
    public function send(Request $request){
    	$this->validate($request,[
    		'contact_email' => 'email',
    		'message'=>'required|min:10',
    	]);
    		Mail::send('mail',['msg' => $request->message,'from_email' => $request->contact_email],function($mail) use ($request) {
    		 $mail->from($request->contact_email);
    	     $mail->to(env('MAIL_USERNAME'))->subject('You have message from Charitypro.am website');
    });
    	return redirect()->back()->with('messages', 'Ձեր հաղորդագրությունը ուղարկվել է,Շնորհակալություն');
    }
}
