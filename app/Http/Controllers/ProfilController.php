<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;

class ProfilController extends Controller
{
    public function index(){

        $page = 'profil';
        $user = User::findOrFail(Auth::user()->id);

        return view('pages.profil.profil',compact('page','user'));
    }

    public function updateProfile(Request $request){

        $this->validate($request, [
            'password'  => 'required|min:8'
        ]);

        $user = User::findOrFail(Auth::user()->id);
        $data = $request->all();
        if($data['password'] != $data['confirm_password']){
            return response()->json([
                'success' => false,
                'message' => 'Konfirmasi password harus sama !'
            ]);
        }
        else{
            $user->update([
                'password'  => Hash::make($data['password'])
            ]);
        }

        return response()->json([
            'success'  => true,
            'message'  => 'Password Berhasil di update !'
        ]);
    }
}
