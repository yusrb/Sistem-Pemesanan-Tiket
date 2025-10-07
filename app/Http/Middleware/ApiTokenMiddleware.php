<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized, Tuan'], 401);
        }

        // set user ke request
        $request->setUserResolver(fn() => $user);

        return $next($request);
    }
}
