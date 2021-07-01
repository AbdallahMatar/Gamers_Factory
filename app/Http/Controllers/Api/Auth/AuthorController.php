<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ControllerHelper;
use App\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    //
    public function login(Request $request)
    {
        $roles = [
            'email' => 'required|email|exists:authors',
            'password' => 'required|min:3',
        ];
        $validator = Validator::make($request->all(), $roles);
        if (!$validator->fails()) {

            $author = Author::where('email', $request->get('email'))->first();

            if (Hash::check($request->get('password'), $author->password)) {
                if ($this->checkActiveTokens($author->id)) {
                    return ControllerHelper::generateResponse(false, 'Login denied, their is an active access!', 400);
                } else {
                    $response = Http::asForm()->post('http://127.0.0.1:8001/oauth/token', [
                        'grant_type' => 'password',
                        'client_id' => '4',
                        'client_secret' => 'c7YBwrDHkONg7JaUlQqG6OiPpW4VASqdvNoJSwCD',
                        'username' => $request->get('email'),
                        'password' => $request->get('password'),
                        'scope' => '*',
                    ]);

                    // $author->token_1 = $response->json()['access_token'];
                    // $author->refresh_token_1 = $response->json()['refresh_token'];

                    $author->setAttribute('token', $response->json()['access_token']);
                    
                    $author->setAttribute('refresh_token', $response->json()['refresh_token']);
                    return response()->json([
                        'status' => true,
                        'message' => 'LOGGED_IN_SUCCESSFULLY',
                        'object' => $author
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
        $token = $request->user('author')->token();
        $refreshTokenRevoked = DB::table('oauth_refresh_tokens')->where('access_token_id', $token->id)->update([
            'revoked' => true
        ]);
        if ($refreshTokenRevoked) {
            $isRevoked = $token->revoke();
            if ($isRevoked) {
                return ControllerHelper::generateResponse(true, 'Logged out successfully', 200);
            } else {
                return ControllerHelper::generateResponse(false, '', 400);
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
}
