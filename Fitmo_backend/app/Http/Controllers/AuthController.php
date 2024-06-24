<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Granam\CzechVocative\CzechName;

class AuthController extends Controller
{

    public function login(Request $request)
    {

        $credentials = ['email' => $request->credentials["email"], 'password' => $request->credentials["password"]];
        if (Auth::attempt($credentials)) {
            if (!Auth::user()["is_email_verified"]) return response('Není ověřen email.', 403);

            $token = hash('sha256', Str::random(60));
            Auth::user()->forceFill([
                "remember_token" => $token
            ])->save();
            return ['token' => $token, "updated_at" => Auth::user()->updated_at, "name" => Auth::user()->name, "rights" => Auth::user()->rights];
        } else {
            return response('Nesprávné přihlašovací údaje.', 401);
        }
    }

    protected function register(Request $request)
    {
        if (User::where('email', '=', $request->userData['email'])->first()) {
            return response('Email je již používaný.', 409);
        }

        //todo dodělat když jsou prázdný inputy a uživatel je zmrd
        $name = new CzechName();
        $nameExploded = explode(" ", $request->userData['name']);
        $vocativedFullName = "";
        foreach ($nameExploded as $namePart) {
            $vocativedFullName .= " " . $name->vocative($namePart);
        }

        $token = hash('sha256', Str::random(60));

        $user = User::create([
            'name' => $request->userData['name'],
            'email' => $request->userData['email'],
            'password' => Hash::make($request->userData['password']),

        ]);

        $user->forceFill([
            "remember_token" => $token
        ])->save();


        // email data
        $email_data = array(
            'name' => $vocativedFullName,
            'email' => $request->userData['email'],
            "token" => $token
        );

        // send email with the template
        Mail::send('confirmation_email', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject('Potvrďte svůj email pro přihlášení na stránce Fitmo.cz')
                ->from('obchod@fitmo.cz', 'Fitmo');
        });

        return $user;
    }
}
