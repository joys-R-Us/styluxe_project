<?php
// app/Http/Controllers/Auth/ForgotPasswordController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMailer;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{

    public function showForgotPasswordForm()
    {
        return view('forgotPassword');
    }

    public function handleForgotPassword(Request $request)
    {
        


        $otp = "101010";

        // return back()->with('error', 'Failed to send OTP. Please try again.');

        // $user = User::where('email', $request->email)->first();
        // $user->otpCode = $otp;
        // $user->save();

        $name = "Aradillos and Palacios";


        try{
            Mail::to($request->email)->send(new OtpMailer($otp, $name));
            Log::info('OTP email sent to ' . $request->email);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $request->email . ' - ' . $e->getMessage());
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }

        return back()->with('status', 'OTP has been sent to your email address.');
    }


    public static function generateOTP(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

}