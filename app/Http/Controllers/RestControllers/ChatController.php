<?php

namespace App\Http\Controllers\RestControllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function getChat(Request $request)
    {
        $affected = Chat::where('user_id', $request->currentUser->id)->where('destination', 'toUser')->update(array('status' => 'read'));
        $chat = Chat::where('user_id', $request->currentUser->id)->orderBy('created_at', 'DESC')->paginate(15);

        if (!$chat) {
            $data = ['statusCode' => '404', 'message' => 'not found', 'result' => null];
            return response()->json($data, $data['statusCode']);
        }

        $data['statusCode'] = 200;
        $data['message'] = 'Success!';
        $data['result'] = $chat;

        return response()->json($data, $data['statusCode']);
    }

    public function sendFromUser(Request $request)
    {
        $rules = [
            'message' => 'required|string'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ( $validator->fails() ) {
            $data['message'] = $validator->errors();
            $data['statusCode'] = 400;
            $data['result'] = null;
            return response()->json($data,$data['statusCode']);
        }

        $chat = Chat::create([
            'user_id' => $request->currentUser->id,
            'message' => $request['message'],
            'destination' => 'fromUser',
        ]);

        $data['message'] = 'Success!';
        $data['statusCode'] = 200;
        $data['result'] = $chat;

        return response()->json($data, $data['statusCode']);
    }

    public function sendToUser(Request $request)
    {
        $rules = [
            'message' => 'required|string'
        ];

        $validator = $this->validator($request->all(), $rules);

        if ( $validator->fails() ) {
            $data['message'] = $validator->errors();
            $data['statusCode'] = 400;
            $data['result'] = null;
            return response()->json($data,$data['statusCode']);
        }

        $chat = Chat::create([
            'user_id' => $request['user_id'],
            'message' => $request['message'],
            'destination' => 'toUser',
        ]);

        $data['message'] = 'Success!';
        $data['statusCode'] = 200;
        $data['result'] = $chat;

        return response()->json($data, $data['statusCode']);
    }
}
