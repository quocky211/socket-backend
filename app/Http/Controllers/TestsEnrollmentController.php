<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\TestEnrollment;
use Illuminate\Support\Facades\Notification;
class TestsEnrollmentController extends Controller
{   
    public function sendTestNotification()
    {   
        // get first user
        $user = User::first();
        // definition notification
        $enrollmentData = [
            'body' => 'You have recieved a mail to test notificationn',
            'enrollmentText' => 'What are your name?',
            'url' => url('/'),
            'thankyou' => 'You have one day left to enroll this notification',
        ];
        // send mail 
        Notification::send($user,new TestEnrollment($enrollmentData));

        return view('welcome');
    }
}
