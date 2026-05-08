<?php

namespace App\Livewire;

use Illuminate\Support\Number;
use Livewire\Component;

class EditExpense extends Component
{
    public $spend;
    public $name;
    public $amount;
    public $platform_id;
    public $status_id;
    public $iteration;

    public function mount($spend = null, $iteration = null)
    {
        $this->iteration = $iteration;
        $this->spend = $spend;
        $this->name = $spend->name;
        $this->amount = $spend->amount;
        $this->platform_id = $spend->platform;
        $this->status_id = $spend->status;
    }

    public function updated($name, $value)
    {
        $this->spend->update([
            $name => $value
        ]);
    }

    public function render()
    {
        return view('livewire.edit-expense');
    }
}
