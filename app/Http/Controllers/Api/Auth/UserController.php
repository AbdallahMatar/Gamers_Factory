<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        $roles = [
            'name' => 'required|string|min:3|max:10',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
            'gender' => 'required|string|in:Male,Female',
            'birth_date' => 'required|date',
            'image' => 'nullable|image',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $user = new User();
            $user->name = $request->get('name');
            $user->email = $request->get('email');
            $user->password = Hash::make($request->get('password'));
            $user->gender = $request->get('gender');
            $user->birth_date = $request->get('birth_date');

            if ($request->hasFile('image')) {
                $userImage = $request->file('image');
                $imageName = time() . '_' . $request->get('email') . '.' . $userImage->getClientOriginalExtension();
                $userImage->move('images/user/', $imageName);
                $user->image = $imageName;
            }

            $isSaved = $user->save();
            if ($isSaved) {
                $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => '2',
                    'client_secret' => 'r40DS4y6oXJ5yo1JPCMlmIbKnrDN5xo4oEGRTMCK',
                    'username' => $request->get('email'),
                    'password' => $request->get('password'),
                    'scope' => '*',
                ]);

                $user->setAttribute('token', $response->json()['access_token']);
                $user->setAttribute('refresh_token', $response->json()['refresh_token']);

                return response()->json([
                    'status' => true,
                    'message' => 'REGISTERED_SUCCESSFULLY',
                    'object' => $user
                ], 200);
            } else {
                return ControllerHelper::generateResponse(false, 'Error credentials', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, $validator->getMessageBag()->first(), 400);
        }
    }

    public function login(Request $request)
    {
        $roles = [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:3',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $user = User::where('email', $request->get('email'))->first();
            if (Hash::check($request->get('password'), $user->password)) {
                $this->revokePreviousTokens($user->id);
<<<<<<< HEAD
                $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => '2',
                    'client_secret' => 'r40DS4y6oXJ5yo1JPCMlmIbKnrDN5xo4oEGRTMCK',
                    'username' => $request->get('email'),
                    'password' => $request->get('password'),
                    'scope' => '*',
                ]);
=======
                    $response = Http::asForm()->post('https://gamersfactory-api.optimalsolution.tech/oauth/token', [
                        'grant_type' => 'password',
                        'client_id' => '2',
                        'client_secret' => 'PRGno8nogUZLjWaFr8DO2VgYTc0nNCi58AzQ2EMd',
                        'username' => $request->get('email'),
                        'password' => $request->get('password'),
                        'scope' => '*',
                    ]);

                    $user->setAttribute('token', $response->json()['access_token']);
                    $user->setAttribute('refresh_token', $response->json()['refresh_token']);
                    return response()->json([
                        'status' => true,
                        'message' => 'LOGGED_IN_SUCCESSFULLY',
                        'object' => $user
                    ], 200);
>>>>>>> 4962ca1 (cpanel edits)

                $user->setAttribute('token', $response->json()['access_token']);
                $user->setAttribute('refresh_token', $response->json()['refresh_token']);
                return response()->json([
                    'status' => true,
                    'message' => 'LOGGED_IN_SUCCESSFULLY',
                    'object' => $user
                ], 200);
            } else {
                return ControllerHelper::generateResponse(false, 'Error credentials', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, $validator->getMessageBag()->first(), 400);
        }
    }

    // public function logout(Request $request)
    // {
    //     $request->user('user')->token()->revoke();
    //     return ControllerHelper::generateResponse(true, 'Logged out successfully', 200);
    // }

    public function logout(Request $request)
    {
        $token = $request->user('user')->token();
        $refreshTokenRevoked = DB::table('oauth_refresh_tokens')->where('access_token_id', $token->id)->update([
            'revoked' => true
        ]);
        if ($refreshTokenRevoked) {
            $isRevoked = $token->revoke();
            if ($isRevoked) {
                return ControllerHelper::generateResponse(true, 'Logged out successfully', 200);
            } else {
                return ControllerHelper::generateResponse(false, 'Error credentials', 400);
            }
        }
    }

    private function revokePreviousTokens($userId)
    {
        DB::table('oauth_access_tokens')
            ->where('user_id', $userId)
            ->update([
                'revoked' => true
            ]);
    }

    private function checkActiveTokens($userId)
    {
        return DB::table('oauth_access_tokens')
            ->where('user_id', $userId)
            ->where('revoked', false)
            ->count() >= 1;
    }

    private function generateToken($user, $message)
    {
        $tokenResult = $user->createToken('Game-User');
        $token = $tokenResult->accessToken;

        $user->setAttribute('token', $token);
        return response()->json([
            'status' => true,
            'message' => $message,
            'object' => $user,
        ]);
    }
}
