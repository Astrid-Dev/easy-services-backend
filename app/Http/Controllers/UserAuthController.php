<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\MultipartFormRequest;
use App\Models\ServiceProvider;
use App\Models\Organization;

class UserAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:simple_user', ['except' => ['login', 'register', 'updateProfile']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|between:6,20',
            'device_token' => 'sometimes|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth('simple_user')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'names' => 'sometimes|string|between:3,80|unique:users',
            'phone_number' => 'sometimes|string|between:9,18|unique:users',
            'username' => 'sometimes|string|between:2,20|unique:users',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|between:6,20',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function updateProfile(Request $req, $id) {
        $searched_user = User::findOrFail($id);
        $request = $req::createFromGlobals();
        // $validator = Validator::make($request->all(), [
        //     'username' => 'sometimes|string|between:2,20|unique:users',
        //     'names' => 'sometimes|string|between:3,80|unique:users',
        //     'phone_number' => 'sometimes|string|between:9,18|unique:users',
        //     'email' => 'sometimes|string|email|max:100|unique:users',
        // ]);

        // if($validator->fails()){
        //     return response()->json($validator->errors()->toJson(), 400);
        // }

        $saved_path= ($searched_user->profile && $searched_user->profile !== '') ? $searched_user->profile : '';

        if ($profile = $request->file('profile')) {
            $destinationPath = public_path().'/profiles/';
            $filename = $this->crypto_rand_secure().$profile->getClientOriginalExtension();
            $profile->move($destinationPath, $filename);
            // final url to store into database
            $saved_path = "profiles/".$filename;
        }

        $searched_user->username = $request->username ? $request->username : $searched_user->username;
        $searched_user->names = $request->names ? $request->names : $searched_user->names;
        $searched_user->phone_number = $request->phone_number ? $request->phone_number : $searched_user->phone_number;
        $searched_user->email = $request->email ? $request->email : $searched_user->email;
        // $searched_user->profile = $saved_path;

        $searched_user->save();


        return response()->json([
            'message' => 'User successfully updated',
            'user' => $searched_user
        ], 201);
    }

    private function crypto_rand_secure($min = 10000000, $max = 99999999)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }


    public function updatePassword(Request $request, $id) {
        $searched_user = User::findOrFail($id);

        $searched_user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|between:6,20',
            'new_password' => 'required|string|confirmed|between:6,20',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $is_same_passwords = auth('simple_user')->attempt([
            "username" => $searched_user->username,
            "names" => $searched_user->names,
            "email" => $searched_user->email,
            "phone_number" => $searched_user->phone_number,
            "password" => $request->old_password,
        ]);

        if(!$is_same_passwords){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $searched_user->password = bcrypt($request->new_password);
        $searched_user->save();

        return response()->json([
            'message' => 'User successfully updated',
            'user' => $searched_user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth('simple_user')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth('simple_user')->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        $user = auth('simple_user')->user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();
        if($provider){
            $provider->applications = $provider->load('applications');
        }
        $user->is_provider = !empty($provider);
        $user->provider = $provider;
        return response()->json($user);
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        $user = auth('simple_user')->user();

        if($user && $user->role === 'PROVIDER'){
            $provider = ServiceProvider::where('user_id', $user->id)->first();
            if($provider){
                $provider->applications = $provider->load('applications');
            }
            $user->is_provider = !empty($provider);
            $user->provider = $provider;
        }
        else if($user && $user->role === 'ORGANIZATION'){
            $organization = Organization::where('user_id', $user->id)->first();
            if($organization){
                $organization->applications = $organization->load('applications');
            }
            $user->is_organization = !empty($organization);
            $user->organization = $organization;
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('simple_user')->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }
}
