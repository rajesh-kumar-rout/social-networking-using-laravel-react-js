<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConvertResponseToCamelCase
{
    public function getInCamelCase($data)
    {
        $replaced = [];

        foreach ($data as $key => $value) {
            
            $replaced[Str::camel($key)] = (($value == "null" && ($value != false && $value != true)) || is_null($value)) ? "" : (is_array($value) ? $this->getInCamelCase($value) : $value);
            // $replaced[Str::camel($key)] = is_array($value) ? $this->getInCamelCase($value) : $value;
        }

        return $replaced;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        return $response->setContent(json_encode(
            $this->getInCamelCase(json_decode($response->getContent(), true))
        ));
    }
}
