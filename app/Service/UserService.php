<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserService
{
    private $token_key = 'my-token';

    public function create(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $this->uploadAvatar($request)
        ]);

        return $user;
    }

    public function update($id, Request $request)
    {
        $user = User::where('id', $id)->firstOrFail();

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'avatar' => $this->uploadAvatar($request)
        ]);

        return $user;
    }

    public function changePasswrd($id, Request $request)
    {
        $user = User::where('id', $id)->firstOrFail();

        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);

            return true;
        }

        return false;
    }

    public function uploadAvatar(Request $request)
    {
        $fileName = null;
        if ($request->hasfile('avatar')) {
            $fileName = time() . '_' . $request->avatar->getClientOriginalName();
            $request->file('avatar')->storeAs('uploads/users/avatar', $fileName, 'public');
        }

        return $fileName;
    }

    public function sendMail($user)
    {
        Mail::send('emails.user_register', ['user' => $user], function ($m) use ($user) {
            $m->from('test@app.com', 'Test APP');
            $m->to($user->email, $user->name)->subject('registrasi sukses');
        });
    }

    public function sendSMS($user)
    {
        $basic  = new \Vonage\Client\Credentials\Basic("a4f7ff15", "5MgvlEmPrG6Fn6lm");
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("6289687259670", 'APP-TEST', 'selamat bergabung ')
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            return "The message was sent successfully\n";
        } else {
            return "The message failed with status: " . $message->getStatus() . "\n";
        }
    }

    public function authCheck($email, $password)
    {
        return Auth::attempt(['email' => $email, 'password' => $password]);
    }

    public function getAuthUser()
    {
        return Auth::user();
    }

    public function createToken($user)
    {
        return $user->createToken($this->token_key)->plainTextToken;
    }
}
