<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Foyer;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function essais() {
        // $foyer = Foyer::find(1);
        $today = Carbon::today();
        return response([
            'message' => "Test",
            'date' => Carbon::today()->toDateString()
        ]);
    }
    // Register
    public function register(Request $request) {
        //validation
        $valid = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Créer un utilisateur
        $user = User::create([
            'name' => $valid['name'],
            'email' => $valid['email'],
            'password' => bcrypt($valid['password']),
        ]);

        // retourner l'utilisateur et le token
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken,
        ]);
    }

    // Login
    public function login(Request $request) {
        //validation
        $valid = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($valid)) {
            return response([
                'errors' => 'Identifiant incorrect',
            ], 403);
        }

        // retourner l'utilisateur et le token
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken,
        ]);
    }

    // logout
    public function logout() {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logout succes',
        ], 200);
    }

    // Get user details
    public function user() {
        $user = auth()->user();
        
        $foyer = Foyer::Where("admin_id", auth()->user()->id)->first();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'foyer_id' => isset($user->foyer_id) ? $user->foyer_id : 0,
                'email' => $user->email,
                'foyer' =>  $user->foyer,
                'active' =>  $user->active,
                'mode' =>  $user->mode,
                'profil' =>  $user->profil,
                'accountType' =>  $foyer?"admin":"user"
            ],
        ]);
    }
    
}
