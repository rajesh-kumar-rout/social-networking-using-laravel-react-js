<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Token;
use Illuminate\Http\Request;

class AuthServiceProvider extends ServiceProvider
{

    protected $policies = [
    ];

    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('token', function (Request $request) {

            $token = Token::where('token', $request->header('authorization'))->first();

            return $token ? User::where('id', $token->user_id)->first() : null;
        });
    }
}
