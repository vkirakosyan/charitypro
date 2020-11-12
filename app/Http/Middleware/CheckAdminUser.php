<?php

namespace App\Http\Middleware;

use Closure;
use App\Enum\UserRole;

class CheckAdminUser
{
    use \App\Enum\Traits\CheckAdminUser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->isAdmin()) {
            abort(404);
        }

        return $next($request);
    }
}
