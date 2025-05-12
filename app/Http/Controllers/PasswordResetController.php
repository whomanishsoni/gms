<?php

namespace App\Http\Controllers;

use App\EmailLog;
use DB;
use Mail;
use App\User;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
	//password reset link
	public function forgotpassword(Request $request)
	{

		$token = $request->_token;
		$email = $request->email;
		$user = DB::table('users')->where('email', '=', $email)->first();
   
		if ($user != '') {
			try {
				$name = $user->name;
				$pass = $user->password;

				$actual_link = $_SERVER['HTTP_HOST'];
				$startip = '0.0.0.0';
				$endip = '255.255.255.255';
				$link = url('passwords/reset/' . $token . '/' . $email);
				$email_content = "To Reset Your Password Please Click..." . $link;
				$mail_sub = "Reset Password";
				/*Env mail from*/ 
				// Clear the config cache to reload the latest .env values
				\Artisan::call('config:clear');
				\Artisan::call('cache:clear');
				$mail_send_from = env('MAIL_FROM_ADDRESS');     //"cakephp.projects@gmail.com";
				
				$data = array(
					'email' => $email,
					'password' => $pass,
					'name' => $name,
					'token' => $token
				);
				
				if (($actual_link == 'localhost' || $actual_link == 'localhost:8080') || ($actual_link >= $startip && $actual_link <= $endip)) {
					
					Mail::send('home', $data, function ($message) use ($data) {
						$message->from($configData['MAIL_FROM_ADDRESS'], 'Reset Password');   
						$message->to($data['email'])->subject("Reset Password");
					});
				} else {
					//Live format email
					$headers = "MIME-Version: 1.0\r\n";
					$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\r\n";
					$headers .= 'From:' . $mail_send_from . "\r\n";

					$data = mail($email, $mail_sub, $email_content, $headers);
				}
						$emailLog = new EmailLog();
						$emailLog->recipient_email = $data['email'];
						$emailLog->subject = $data['mail_sub1'];
						$emailLog->content = $data['email_content1'];
						$emailLog->save();
			} catch (\Exception $e) {
			}
			return redirect('/password/reset')->with('message', 'Your password reset link has been sent to your email address !');
		} else {

			return redirect('/password/reset')->with('message', 'Email Address you have entered is not match with our records !');
		}
	}

	//reset password form
	public function geturl($token, $email)
	{
		return view('auth.passwords.reset', compact('token', 'email'));
	}

	//new password
	public function passwordnew(Request $request)
	{
		$this->validate($request, [
			'password' => 'required|min:6|max:12|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
			'password_confirmation' => 'required|same:password',
			'password.required' => 'Password Field is required',
			'password_confirmation.required' => 'Confirm Password Field is required',
			'password_confirmation.same' => 'Confirm Password must be same as password',
		]);
		$email = $request->email;
		$user = DB::table('users')->where('email', '=', $email)->first();
		$id = $user->id;

		$user = User::find($id);
		//$user->password=bcrypt(Input::get("password_confirmation"));
		$user->password = bcrypt($request->password_confirmation);
		$user->save();
		return redirect('/')->with('message', 'Your Password Has Been Successfully Changed !');
	}
}
