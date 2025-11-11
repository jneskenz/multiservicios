<?php

namespace App\Livewire\Workspace;

use Livewire\Component;
use Livewire\WithPagination;

class RolesTable extends Component
{

    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $estadoFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $roleToDelete = null;
    public $showDeleteModal = false;

    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEstadoFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->estadoFilter = '';
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
        $this->resetPage();
    }

    public function updatingSortField()
    {
        $this->resetPage();
    }

    public function deleteRole($roleId)
    {
        $this->roleToDelete = $roleId;
        $this->showDeleteModal = true;
    }

    public function confirmDelete()
    {
        if ($this->roleToDelete) {
            $role = \Spatie\Permission\Models\Role::findById($this->roleToDelete);
            if ($role) {
                $role->delete();
                session()->flash('success', 'Rol eliminado exitosamente.');
            } else {
                session()->flash('error', 'Rol no encontrado.');
            }
        }
        $this->showDeleteModal = false;
        $this->roleToDelete = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->roleToDelete = null;
    }

    public function toggleStatus($roleId)
    {
        $role = \Spatie\Permission\Models\Role::findById($roleId);
        if ($role) {
            // Aquí puedes implementar la lógica para activar/desactivar el rol
            // Por ejemplo, podrías tener un campo 'activo' en una tabla relacionada
            $role->activo = !$role->activo;
            $role->save();

            session()->flash('success', 'Estado del rol actualizado.');
        } else {
            session()->flash('error', 'Rol no encontrado.');
        }
    }

    
    public function render()
    {

        $query = \Spatie\Permission\Models\Role::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->estadoFilter) {
            $query->where('activo', $this->estadoFilter);
        }

        $roles = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.workspace.roles-table', compact('roles'));
    }
}
