<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PDFsending;
use App\Mail\RegisteredNotification;
use PDF;

class PDFConotroller extends Controller
{

    public function index()
    {
        return view('myPDF');
//        $pdf = [];
//        $pdf[] = [
//
//            'name' => 'Ahtisham',
//            'email' => 'ahtisham@amzonestep.com'
//
//        ];
//
//        Mail::to('ahtisham@amzonestep.com')->send(new PDFsending($pdf));

//        $pdf = PDF::loadView('myPDF', $data);
//         $pdf->download('itsolutionstuff.pdf');
    }

    public function generatePDF()

    {

        $data = [

            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y')

        ];

        // Mail::to('ahtisham@amzonestep.com')->send(new RegisteredNotification($data));

        return view('email_template.registered_notification');

    }

}
