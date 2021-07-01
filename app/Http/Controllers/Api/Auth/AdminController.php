<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //
    public function login(Request $request)
    {
        $roles = [
            'email' => 'required|email|exists:admins',
            'password' => 'required|min:3',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {
            $admin = Admin::where('email', $request->get('email'))->first();
            if (Hash::check($request->get('password'), $admin->password)) {
                if ($this->checkActiveTokens($admin->id)) {
                    return ControllerHelper::generateResponse(false, 'Login denied, their is an active access!', 400);
                } else {
                    $response = Http::asForm()->post('https://gamersfactory-api.optimalsolution.tech/oauth/token', [
                        'grant_type' => 'password',
                        'client_id' => '3',
<<<<<<< HEAD
                        'client_secret' => 'AU4OTz3LgqS298EVN0T80qF2GNN0VL37hN53nK9r',
=======
                        'client_secret' => 'sfYQiixwIv7zW1hD8kFMsFb6mKKBf2dkORwhfMtv',
>>>>>>> 4962ca1 (cpanel edits)
                        'username' => $request->get('email'),
                        'password' => $request->get('password'),
                        'scope' => '*',
                    ]);

                    $admin->setAttribute('token', $response->json()['access_token']);
                    $admin->setAttribute('refresh_token', $response->json()['refresh_token']);
                    return response()->json([
                        'status' => true,
                        'message' => 'LOGGED_IN_SUCCESSFULLY',
                        'object' => $admin
                    ], 200);
                }
            } else {
                return ControllerHelper::generateResponse(false, 'Error credentials', 400);
            }
        } else {
            return ControllerHelper::generateResponse(false, $validator->getMessageBag()->first(), 400);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user('admin')->token();
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

    private function checkActiveTokens($userId)
    {
        return DB::table('oauth_access_tokens')
                ->where('user_id', $userId)
                ->where('revoked', false)
                ->count() >= 1;
    }
//
//    private function generateToken($user, $message)
//    {
//        $tokenResult = $user->createToken('Game-Admin');
//        $token = $tokenResult->accessToken;
//
//        $user->setAttribute('token', $token);
//        return response()->json([
//            'status' => true,
//            'message' => $message,
//            'object' => $user,
//        ]);
//    }
}
