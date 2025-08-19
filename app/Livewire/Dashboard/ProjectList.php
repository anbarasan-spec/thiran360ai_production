<?php
namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectList extends Component
{
    use WithPagination;

    public $search    = '';
    public $showPopup = false;
    public $popupMode = 'create'; // create or edit
    public $editData  = [];
    public $editingId = null;

    public $users = []; // For admin to assign projects

    protected $queryString = ['search'];

    protected $rules = [
        'editData.project_name'   => 'required|string|max:255',
        'editData.start_date'     => 'required|date',
        'editData.end_date'       => 'required|date|after_or_equal:editData.start_date',
        'editData.project_price'  => 'nullable|numeric|min:0',
        'editData.project_status' => 'required|in:pending,in progress,complete,canceled',
        'editData.user_id'        => 'required|exists:users,id',
    ];

    public function mount()
    {
        if (auth()->user()->role === 'admin') {
            $this->users = DB::table('users')->select('id', 'name')->get();
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // --- Add/Edit ---
    public function startCreate()
    {
        $this->reset(['editingId']);
        $this->editData = [
            'project_name'   => '',
            'start_date'     => '',
            'end_date'       => '',
            'project_price'  => 0,
            'project_status' => 'pending',
            'user_id'        => auth()->id(),
        ];
        $this->popupMode = 'create';
        $this->showPopup = true;
    }

    public function startEdit($id)
    {
        $project = DB::table('user_projects')->find($id);
        if (! $project) return;

        $this->editingId = $id;
        $this->editData = (array) $project;
        $this->editData['project_status'] = (string) $this->editData['project_status'];
        $this->popupMode = 'edit';
        $this->showPopup = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'project_name'   => (string) $this->editData['project_name'],
            'start_date'     => date('Y-m-d', strtotime($this->editData['start_date'])),
            'end_date'       => date('Y-m-d', strtotime($this->editData['end_date'])),
            'project_price'  => floatval($this->editData['project_price'] ?? 0),
            'project_status' => (string) trim($this->editData['project_status']),
            'user_id'        => intval($this->editData['user_id'] ?? auth()->id()),
            'updated_at'     => now(),
        ];

        if ($this->popupMode === 'edit' && $this->editingId) {
            DB::table('user_projects')->where('id', $this->editingId)->update($data);
            session()->flash('success', 'Project updated successfully!');
        } else {
            $data['created_at'] = now();
            DB::table('user_projects')->insert($data);
            session()->flash('success', 'Project created successfully!');
        }

        $this->cancel();
    }

    public function cancel()
    {
        $this->reset(['editingId', 'editData', 'popupMode']);
        $this->showPopup = false;
    }

    // --- Direct Delete ---
    public function deleteProject($id)
    {
        if ($id && DB::table('user_projects')->where('id', $id)->exists()) {
            DB::table('user_projects')->where('id', $id)->delete();
            session()->flash('success', 'Project deleted successfully!');
        }
    }

    public function updateStatus($projectId, $status)
    {
        $validStatuses = ['pending', 'in progress', 'complete', 'canceled'];
        if (! in_array($status, $validStatuses)) return;

        DB::table('user_projects')
            ->where('id', $projectId)
            ->where('user_id', Auth::id())
            ->update(['project_status' => $status]);

        session()->flash('success', 'Project status updated successfully.');
    }

    public function render()
    {
        $user = auth()->user();

        $projects = DB::table('user_projects')
            ->join('users', 'user_projects.user_id', '=', 'users.id')
            ->select('user_projects.*', 'users.name as user_name')
            ->when($this->search, fn($q) =>
                $q->where('project_name', 'like', "%{$this->search}%")
                    ->orWhere('project_status', 'like', "%{$this->search}%")
                    ->orWhere('users.name', 'like', "%{$this->search}%")
            )
            ->when($user->role !== 'admin', fn($q) => $q->where('user_id', $user->id))
            ->orderBy('user_projects.created_at', 'desc')
            ->paginate(10);

        return view('livewire.dashboard.project-list', ['projects' => $projects]);
    }
}
