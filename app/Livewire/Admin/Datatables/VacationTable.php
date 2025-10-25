<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\Vacacion;
use Livewire\Component;
use Livewire\WithPagination;

class VacationTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $updatesQueryString = ['search', 'page'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = Vacacion::query()
            ->with(['usuario'])
            ->when($this->search, function ($q) {
                $q->where('observaciones', 'like', '%'.$this->search.'%')
                  ->orWhereHas('usuario', fn($u) => $u->where('name', 'like', '%'.$this->search.'%'));
            })
            ->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.admin.datatables.vacation-table', [
            'vacations' => $query->paginate($this->perPage),
        ]);
    }
}
