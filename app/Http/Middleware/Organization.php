<?php

namespace App\Http\Middleware;

use App\Models\Organization as ModelsOrganization;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Organization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $data = $this->getAuthenticatedUser()->original;

        if(isset($data['user'])){
            $user = $data['user'];
            $organization = ModelsOrganization::where('user_id', $user->id)->first();

            if($user->role === 'PROVIDER' && $organization){
                return $next($request);
            }
        }

        abort(403);
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
                // return null;
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], 403);
            // return null;

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], 403);
            // return null;

        } catch (JWTException $e) {

            return response()->json(['token_absent'], 403);
            // return null;

        }

        // the token is valid and we have found the user via the sub claim
        // return compact('user');
        return response()->json(compact('user'));
    }
}
