<?php

namespace App\Livewire\Employees;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination;

    public $search = '';

    // Modal state
    public $showUserModal = false, $editMode = false;

    // Form fields
    public $userId, $name, $email, $phone_no, $designation, $salary, $role = 'user', $user_status = 'active', $password;

    protected $queryString = ['search'];

    protected function rules()
    {
        return [
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $this->userId,
            'phone_no'    => 'required|string|max:15',
            'designation' => 'required|string|max:255',
            'salary'      => 'required|numeric|min:0',
            'role'        => 'required|in:admin,user',
            'user_status' => 'required|in:active,inactive',
            'password'    => $this->editMode ? 'nullable|string|min:6' : 'required|string|min:6',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openAddUserModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showUserModal = true;
    }

    public function openEditUserModal($id)
    {
        $user = User::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403); // unauthorized
        }

        $this->resetForm();
        $this->editMode = true;
        $this->showUserModal = true;

        $this->userId      = $user->id;
        $this->name        = $user->name;
        $this->email       = $user->email;
        $this->phone_no    = $user->phone_no;
        $this->designation = $user->designation;
        $this->salary      = $user->salary;
        $this->role        = $user->role;
        $this->user_status = $user->user_status;
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
    }

    public function saveUser()
    {
        $this->validate();

        User::create([
            'name'        => $this->name,
            'email'       => $this->email,
            'phone_no'    => $this->phone_no,
            'designation' => $this->designation,
            'salary'      => $this->salary,
            'role'        => $this->role,
            'user_status' => $this->user_status,
            'password'    => Hash::make($this->password),
        ]);

        $this->closeUserModal();
        session()->flash('success', 'User added successfully.');
    }

    public function updateUser()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403);
        }

        $user->update([
            'name'        => $this->name,
            'email'       => $this->email,
            'phone_no'    => $this->phone_no,
            'designation' => $this->designation,
            'salary'      => $this->salary,
            'role'        => auth()->user()->role === 'admin' ? $this->role : $user->role,
            'user_status' => auth()->user()->role === 'admin' ? $this->user_status : $user->user_status,
            'password'    => $this->password ? Hash::make($this->password) : $user->password,
        ]);

        $this->closeUserModal();
        session()->flash('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        User::findOrFail($id)->delete();
        session()->flash('success', 'User deleted successfully.');
    }

    private function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'phone_no', 'designation', 'salary', 'role', 'user_status', 'password']);
        $this->role        = 'user';
        $this->user_status = 'active';
    }

    public function render()
    {
        $query = User::query()
            ->when($this->search, fn($q) =>
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('designation', 'like', "%{$this->search}%");
                })
            )
            ->orderBy('name');

        if (auth()->user()->role !== 'admin') {
            $query->where('id', auth()->id());
        }

        return view('livewire.employees.employee-list', [
            'employees' => $query->paginate(10),
        ]);
    }
}
