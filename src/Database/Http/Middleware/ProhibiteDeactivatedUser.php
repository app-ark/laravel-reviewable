<?php
namespace AppArk\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use AppArk\Database\Eloquent\Deactivates;
use Illuminate\Contracts\Auth\Authenticatable;

class ProhibiteDeactivatedUser
{
    /**
     * handle
     *
     * @param Request $request
     * @param Closure $next
     * @return void
     */
    public function handle(Request $request, $next)
    {
        /**
         * @var Deactivates $user
         */
        $user = $request->user();
        if ($user && method_exists($user, 'bootDeactivates') && $user->deactivated()) {
            abort(400, 'User deactivated!');
        }
        return $next();
    }
}