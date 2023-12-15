<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DTOs\Requests\RegisterDTO;
use App\Services\Auth\AuthService;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use App\DTOs\Requests\PasswordChangeDTO;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private AuthService $authService, private UserService $userService)
    {
        // $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']); // the email and password column of the table and the request body

        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $userRole = Auth::user()->role;

        $token = Auth::claims(['role' => $userRole])->attempt($credentials);

        if($userRole == 'admin') $isAdmin = 1;
        else $isAdmin = 0;
        return response()->json(['token'=> $token, 'isAdmin' => $isAdmin]);
    }

    public function logout(Request $request)
    {
        return "logout";
        $token = Auth::getToken();
        if($token){
            Redis::set("".$token, 'blacked');
            Redis::expire("".$token, 60 * 60);
            return $token;
        }
        
        return FALSE;
    }

    public function register(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            return response()->json(['message' => 'User already existed'], 201);
        }

        $registerDTO = new RegisterDTO(
            $request->input('email'),
            $request->input('password')
        );

        $user = $this->userService->createUser($registerDTO);

        if ($user) {
            return response()->json(['message' => 'User registered successfully'], 201);
        } else {
            return response()->json(['error' => 'Failed to register user'], 500);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(Auth::user());
    }

    public function changepassword(Request $request)
    {
        /*
            {
            "oldPassword": "string",
            "newPassword": "string1234"
            }
        */
        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');

        // Create an instance of the DTO
        $passwordChangeDTO = new PasswordChangeDTO($oldPassword, $newPassword);
        $userId = Auth::id();
        return $this->authService->changepassword($userId, $passwordChangeDTO);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        /** @var Illuminate\Auth\AuthManager */
        $auth = auth();
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'bearer',
        //     'expires_in' => Auth::factory()->getTTL() * 60
        // ]);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }
}
