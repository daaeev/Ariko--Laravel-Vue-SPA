<?php

namespace App\Http\Middleware;

use App\Services\TokenValidators\interfaces\AuthTokenValidatorInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthWithToken
{
    public function __construct(protected AuthTokenValidatorInterface $validator)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization', '');

        if ($this->validator->validate($token)) {
            return $next($request);
        } else {
            throw new HttpException(401);
        }
    }
}
