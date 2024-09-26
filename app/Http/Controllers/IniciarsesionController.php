<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class IniciarsesionController extends Controller {
    
    public function login(Request $request) {
        $credenciales = $request->validate([
          'email' => ['required','email'],
          'password' => ['required']
        ]);

        if(Auth::attempt($credenciales)) {
          $user = Auth::user();
          $token = $user->createToken('token')->plainTextToken;
          $cookie = cookie('cookie_token', $token, 60 * 24);

            return response(
              [
                "token" => $token,
                "users" => $user::where('email', $request->email)->get()->first(),
                "status" => 200
              ],
                Response::HTTP_OK
            )
            ->withoutCookie($cookie);

        }
        else {

          return response()->json([
              "message" => "El Usuario o Contraseña no son correctos.",
              "status" => 401
            ]);

        }
    }

    public function logOut() {
      $cookie = Cookie::forget('cookie_token');
      return response(["message"=>"Cierre de sesión OK"], Response::HTTP_OK)
             ->withCookie($cookie);
    }
}
