<?php

namespace App\Http\Controllers\Admin;

use App\Firebase;
use App\Models\Chat;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->currentAdmin->role->chats >= Role::ACCESS_READ) {
            return back()->withErrors('У вас нет доступа');
        }
        $users = Chat::leftJoin('chats as chats2', function($q) {
                            $q->on('chats.user_id', '=', 'chats2.user_id')
                                ->on('chats.id', '<', 'chats2.id');
                        })
                        ->select('chats.*')
                        ->where('chats2.id', null)
                        ->orderBy('chats.created_at', 'DESC')
                        ->paginate(10);
        if (isset($request['user_id'])){
            $currentUser = User::findOrFail($request['user_id']);
            $chat = Chat::where('user_id', $currentUser->id)
                        ->orderBy('created_at', 'ASC')
                        ->get();
        } else {
            $currentUser = null;
            $chat = null;
        }
        return view('admin.chat.index', compact('users', 'currentUser', 'chat'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->currentAdmin->role->chats >= Role::ACCESS_CREATE) {
            return back()->withErrors('У вас нет доступа');
        }
        $user = User::whereId($request['user_id'])
            ->pluck('device_token')
            ->toArray();
        Firebase::sendMultiple($user, [
            'body' => $request['message'],
            'title' => 'Новое сообщение от '.$request->currentAdmin->name,
            'sound' => 'default'
        ]);
        Chat::create([
            'user_id' => $request['user_id'],
            'message' => $request['message'],
            'destination' => 'toUser',
        ]);
        return back()->with('success', 'Успешно отправлено');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
