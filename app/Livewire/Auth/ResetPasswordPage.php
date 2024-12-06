<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Illuminate\Support\facades\Password;
use Illuminate\Support\facades\Hash;
use Illuminate\Support\Str;

#[Title('Reset password')]
class ResetPasswordPage extends Component
{
    public $token;

    #[Url]
    public $email;
    public $password;
    public $password_confirmation;

    public function mount($token){
        $this->token = $token;
    }

    public function changePassword(){
        $this->validate([
            'token' => 'required',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,

            ],
            function (User $user, $password) {
                $password = $this->password;
                $user->forceFill([
                    'password'=>Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
    });
        return $status === Password::PASSWORD_RESET ? redirect('/login') : sesion()->flash('error', 'Something went wrong');
    }
    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}