<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Livewire\Component;

class PersonalAccessTokens extends Component
{
    public $name;
    public $newTokenString;

    protected $listeners = ['openModal' => 'autoFocusModalEvent'];

    public function autoFocusModalEvent()
    {
        $this->dispatchBrowserEvent('autoFocusModal');
    }

    public function render()
    {
        return view('livewire.personal-access-tokens', [
            'tokens' => Auth::user()->tokens,
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function createToken(): void
    {
       $this->validate();

       $newToken = Auth::user()->createToken($this->name);

       $this->newTokenString = $newToken->accessToken;

       $this->dispatchBrowserEvent('tokenCreated', $newToken->accessToken);
    }

    public function deleteToken($tokenId): void
    {
        //this needs safety (though the scope of auth::user might kind of do it...)
        //seems like it does, test more
        Auth::user()->tokens()->find($tokenId)->delete();
    }
}
