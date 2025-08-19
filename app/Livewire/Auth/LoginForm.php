<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';
    public string $errorMessage = '';

    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required|min:4',
    ];

    public function mount()
    {
        if (auth()->check()) {
            $this->redirectRoute('dashboard', navigate: true);
        }
    }

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            request()->session()->regenerate();
            return $this->redirectRoute('dashboard', navigate: true);
        }

        $this->errorMessage = 'Invalid credentials.';
    }

    public function logout()
    {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
