<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class Counter extends Component
{
    use WithFileUploads;

    public $count = 0, $name = '', $email = '', $password = '', $password_confirmation = '', $image;

    public function mount($count)
    {
        $this->count = $count;
    }

    public function increment()
    {
        $this->count = $this->count + 1;
    }

    public function decrement()
    {
        $this->count = $this->count - 1;
    }
    
    public function render()
    {
        return view('livewire.counter');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'image' => 'required|image',
            'password' => 'required|min:6'
        ]);

        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        
        session()->flash('status', 'Form Submitted');
    }
}
