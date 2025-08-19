<?php
namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public $name, $email, $phone_no, $designation, $password;
    public $editing   = false;
    public $showModal = true;

    public function mount()
    {
        $user              = Auth::user();
        $this->name        = $user->name;
        $this->email       = $user->email;
        $this->phone_no    = $user->phone_no;
        $this->designation = $user->designation ?? '-';
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function saveProfile()
    {
        $this->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'phone_no'    => 'nullable|string',
            'designation' => 'nullable|string',
            'password'    => 'nullable|string|min:6',
        ]);

        $user = Auth::user();
        $user->update([
            'name'        => $this->name,
            'email'       => $this->email,
            'phone_no'    => $this->phone_no,
            'designation' => $this->designation,
            'password'    => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        session()->flash('message', 'Profile updated successfully.');
        $this->editing = false;
    }

    public function closeModal()
    {
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
