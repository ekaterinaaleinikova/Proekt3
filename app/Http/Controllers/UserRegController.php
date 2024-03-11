<?php

namespace App\Http\Controllers;

use App\Models\UserReg;
use Illuminate\Http\Request;

class UserRegController extends Controller
{
    public function showRegistrationForm()
    {
        return view('registration');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|unique:usersreg',
            'password' => 'required',
        ]);

        // Создаем пользователя
        $user = UserReg::create([
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Пример вывода ответа
        return "Пользователь зарегистрирован: Имя пользователя - $user->username, Пароль - (зашифрован)";
    }
}
