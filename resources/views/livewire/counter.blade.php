<div>
    <div wire:loading>Loading...</div>
    @if (session()->has('status'))
        <span>{{ session('status') }}</span>
    @endif
    <form wire:submit.prevent="submit">
        <input type="text" placeholder="Name" wire:model.defer="name">
        @error('name')
            <span>{{ $message }}</span>
        @enderror <br> <br>
        <input type="email" placeholder="email" wire:model="email">
            @error('email')
            <span>{{ $message }}</span>
        @enderror <br> <br>

        <input type="file" placeholder="image" wire:model="image">
            @error('image')
            <span>{{ $message }}</span>
        @enderror <br> <br>

        @if ($image)
        Photo Preview:
        <img src="{{ $image->temporaryUrl() }}">
    @endif
 

        <input type="password" placeholder="password" wire:model.defer="password">
            @error('password')
            <span>{{ $message }}</span>
        @enderror <br> <br>
        <input type="password_confirmation" placeholder="password_confirmation" wire:model.defer="password_confirmation">
        <button type="submit">Submit</button>
    </form>

    <div x-data="{ open: false }">
        <button @click="open = true">Show More...</button>
 
        <ul x-show="open" @click.outside="open = false">
            <li><button wire:click="archive">Archive</button></li>
            <li><button wire:click="delete">Delete</button></li>
        </ul>
    </div>
</div>

