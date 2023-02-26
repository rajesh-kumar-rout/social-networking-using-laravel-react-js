<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConvertRequestToSnakeCase
{
    public function getInSnakeCase($data)
    {
        $replaced = [];

        foreach ($data as $key => $value) 
        {
            $replaced[Str::snake($key)] = is_array($value) ? $this->getInSnakeCase($value) : $value;
        }

        return $replaced;
    }

    public function handle(Request $request, Closure $next)
    {
        $request->replace($this->getInSnakeCase($request->all()));

        return $next($request);
    }
}
