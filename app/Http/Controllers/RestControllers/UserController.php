<?php

namespace App\Http\Controllers\RestControllers;

use App\Models\User;
use App\Models\Cat;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('phone', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'user_not_found', 'result' => null, 'statusCode' => 404], 404);
            } else {
                $user = JWTAuth::user();
                $user->device_token = $request->get('device_token');
                $user->save();
                $user->token = $token;
                $result['statusCode'] = 200;
                $result['message'] = 'success';
                $result['result'] = $user;
                if ($user->blocked) {
                    $data['statusCode'] = 400;
                    $data['message'] = 'User is blocked';
                    $data['result'] = [];
                    return response()->json($data, $data['statusCode']);
                }
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token', 'result' => null, 'statusCode' => 500], 500);
        }
        return response()->json($result, 200);
    }

    public function socialSignIn(Request $request)
    {
        if ($request->header('x-api-key') == env('API_KEY')) {
            $check = User::where('email', $request->get('email'))->exists();
            if ($check) {
                $rules = [
                    'name' => 'string|max:255',
                    'email' => 'required|email|exists:users,email',
                ];
            } else {
                $rules = [
                    'name' => 'string|max:255',
                    'email' => 'required|email|unique:users,email',
                ];
            }
            $validator = $this->validator($request->all(), $rules);
            if ($validator->fails()) {
                $data['statusCode'] = 400;
                $data['message'] = $validator->errors();
                $data['result'] = [];
                return response()->json($data, $data['statusCode']);
            }


            if ($check) {
                $user = User::where('email', $request->get('email'))->first();
                $user->password = Hash::make('qazplm');
                $user->device_token = $request->get('device_token');
                $user->save();
            } else {
                $user = User::create([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make('qazplm'),
                    'device_token' => $request->get('device_token')
                ]);
            }

            $token = JWTAuth::fromUser($user);
            $user->token = $token;

            $data['statusCode'] = 200;
            $data['message'] = 'Success';
            $data['result'] = $user;
        } else {
            $data['statusCode'] = 401;
            $data['message'] = 'Invalid key';
            $data['result'] = null;
        }
        return response()->json($data, $data['statusCode']);
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|min:10|max:10|unique:users,phone',
            'password' => 'required|string|min:6',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) {
            $data['statusCode'] = 400;
            $data['message'] = $validator->errors();
            $data['result'] = [];
            return response()->json($data, $data['statusCode']);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'password' => Hash::make($request->get('password')),
            'device_token' => $request->get('device_token')
        ]);

        $token = JWTAuth::fromUser($user);
        $user->token = $token;

        return response()->json(compact('user'),200);
    }

    public function changePassword(Request $request)
    {
        if ($request->header('x-api-key') == env('API_KEY')) {
            $rules = [
                'phone' => 'required|exists:users,phone',
                'password' => 'required|string|min:6',
            ];
            $validator  = $this->validator($request->only('phone', 'password'), $rules);
            if ($validator->fails()) {
                $data['statusCode'] = 400;
                $data['message'] = $validator->errors();
                $data['result'] = null;
                return response()->json($data, $data['statusCode']);
            }
            $user = User::wherePhone($request['phone'])->firstOrFail();
            $user->password = Hash::make($request['password']);
            $user->save();
            $data['statusCode'] = 200;
            $data['message'] = 'Success';
            $data['result'] = $user;
        } else {
            $data['statusCode'] = 401;
            $data['message'] = 'Invalid key';
            $data['result'] = null;
        }
        return response()->json($data, $data['statusCode']);
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            } else {
                $payload = JWTAuth::parseToken()->getPayload();
                $expires_at = date('Y-m-d H:i:s', $payload->get('exp'));
                $user['token_expires'] = $expires_at;
                $data['statusCode'] = 200;
                $data['message'] = 'Success!';
                $data['result'] = $user;
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json($data, $data['statusCode']);
    }

    public function getUser(Request $request)
    {
        $user = $request->currentUser;
        $payload = JWTAuth::parseToken()->getPayload();
        $expires_at = date('Y-m-d H:i:s', $payload->get('exp'));
        $user['token_expires'] = $expires_at;
        $data['statusCode'] = 200;
        $data['message'] = 'Success!';
        $data['result'] = $user;
        $data['result']['address'] = UserAddress::where('user_id', $user->id)->with('city')->first();
        return response()->json($data, $data['statusCode']);
    }

    public function userAddress(Request $request)
    {
        $user = $request->currentUser;
        $rules = [
            'cityId' => 'exists:cities,id',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) {
            $result['statusCode'] = 404;
            $result['message'] = $validator->errors();
            $result['result'] = null;
            return response()->json($result,$result['statusCode']);
        }
        $address = UserAddress::where('user_id', $user->id)->first() ?? new UserAddress();
        $address->user_id = $user->id;
        if (isset($request['cityId'])) {
            $address->city_id = $request['cityId'];
        }
        if (isset($request['street'])) {
            $address->street = $request['street'];
        }
        if (isset($request['house'])) {
            $address->house = $request['house'];
        }
        if (isset($request['entrance'])) {
            $address->entrance = $request['entrance'];
        }
        if (isset($request['floor'])) {
            $address->floor = $request['floor'];
        }
        if (isset($request['building'])) {
            $address->building = $request['building'];
        }
        if (isset($request['flatNumber'])) {
            $address->flat_number = $request['flatNumber'];
        }
        if (isset($request['code'])) {
            $address->code = $request['code'];
        }
        $address->save();
        $user['address'] = $address;
        $data['statusCode'] = 200;
        $data['message'] = 'success';
        $data['result'] = $user;
        return response()->json($data, $data['statusCode']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->currentUser;
        $uniqueEmail = $request->currentUser->email == $request['email'] ? '' : '|unique:users,email';
        $uniquePhone = $request->currentUser->phone == $request['phone'] ? '' : '|unique:users,phone';
        $rules = [
            'birthDate' => 'date',
            'name' => 'string',
            'phone' => 'string|min:10|max:10'.$uniquePhone,
            'email' => 'email'.$uniqueEmail,
            'password' => 'string|min:6',
            'device_token' => 'max:10'
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) {
            $result['statusCode'] = 404;
            $result['message'] = $validator->errors();
            $result['result'] = null;
            return response()->json($result,$result['statusCode']);
        }
        if (isset($request['birthDate'])){
            $user->birth_date = $request['birthDate'];
        }
        if (isset($request['name'])){
            $user->name = $request['name'];
        }
        if (isset($request['email'])){
            $user->email = $request['email'];
        }
        if (isset($request['phone'])){
            $user->phone = $request['phone'];
        }
        if (isset($request['password'])){
            $user->password = Hash::make($request['password']);
        }
        $user->save();
        $user['address'] = UserAddress::where('user_id', $user->id)->first();

        $data['statusCode'] = 200;
        $data['message'] = 'success';
        $data['result'] = $user;
        return response()->json($data, $data['statusCode']);
    }
}
