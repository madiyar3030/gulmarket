@extends('admin.layouts.app', [ 'title' => 'Чат', 'active_chat' => 'active' ])

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 chat">
                    <div class="col-md-3 vh100 padding-0">
                        <div class="chat-header p-l-15">
                            <h3>Чат</h3>
                        </div>
                        <div class="chat-body">
                            <ul class="">
                                @foreach($users as $user)
                                    <li class="chats {{ isset($currentUser->id) && ($currentUser->id == $user->user_id) ? 'active' : ''}}" onclick="location.href='{{route('chat.index', ['user_id' => $user->user_id])}}'">
                                        <div class="col-xs-2">
                                            <img src="{{asset($user->user->thumb ?? 'admin-vendor/images/user.png')}}" alt="">
                                        </div>
                                        <div class="col-xs-8">
                                            <h5>{{$user->user->name}}</h5>
                                            <span>{{$user->message}}</span>
                                        </div>
                                        <div class="col-xs-2">
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <div style="display: flex; justify-content: center">
                                {{$users->appends(request()->all())->links()}}
                            </div>
                        </div>
                    </div>
                    @if(isset($currentUser))
                        <div class="col-md-9 vh100 chat-message">
                            <div class="chat-title col-xs-12">
                                <img src="{{asset($currentUser->thumb ?? 'admin-vendor/images/user.png')}}" alt="">
                                <div>
                                    <h4>{{$currentUser->name}}</h4>
                                    <span>Online</span>
                                </div>
                            </div>
                            <div class="chat-history">
                                @php
                                    $carbon = new \Carbon\Carbon;
                                    $carbon::setLocale('ru');
                                @endphp
                                @foreach($chat as $item)
                                    <div class="message-item {{$item->destination == 'toUser' ? 'outgoing-message' : ''}}">
                                        <div class="message-content">
                                            {{$item->message}}
                                        </div>
                                        <div class="message-action">
                                            {{$carbon->parse($item->created_at)->diffForHumans()}}
                                            {{--->format('H:i d M, Y')--}}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="chat-form">
                                <form action="{{route('chat.store')}}" id="send-message" method="POST">
                                    @csrf
                                    @method('post')
                                    <div class="input-group">
                                        <input type="hidden" name="user_id" value="{{request('user_id')}}">
                                        <div class="form-line">
                                            <textarea rows="1" class="form-control no-resize auto-growth" placeholder="Please type what you want... And please don't forget the ENTER key press multiple times :)" name="message"></textarea>
                                        </div>
                                        <span class="input-group-addon" onclick="document.getElementById('send-message').submit();">
                                            <i class="material-icons">send</i>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <style>
        .vh100{
            height: calc(100vh - 130px);
        }
        .chat .col-md-3 {
            border-radius: 5px;
            background-color: #fff;
        }
        .chat .col-md-3,
        .chat .col-md-9 {
        }
        .chat .chat-header{
        }
        .chat .chat-body {
            height: calc(100vh - 190px);
            overflow-y: auto;
        }
        .chat .chat-body ul {
            list-style-type: none;
            padding: 0;
        }
        .chat .chat-body ul li.chats{
            border-bottom: 1px solid #8c5d5d50;
            height: 80px;
            padding: 15px;
            cursor: pointer;
        }
        .chat .chat-body ul li.chats.active{
            border-left: 5px solid #3db16b;
        }
        .chat .chat-body ul li.chats:hover{
            background-color: #8c5d5d50;
        }
        .chat .chat-body ul li.chats .col-xs-2{
            padding: 0;
        }
        .chat .chat-body .col-xs-8{
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            height: 100%;
        }
        .chat .chat-body .col-xs-10 h5{
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }
        .chat .chat-body .col-xs-10 p{
            color: #969696;
            font-weight: 400;
            font-size: 1em;
        }
        .chat .chat-body .col-xs-2 img{
            border-radius: 50%;
            max-width: 50px;
            max-height: 50px;
            display: block;
        }
        .chat .chat-message {
            display: flex;
            flex-direction: column;
        }
        .chat .chat-title {
            display: flex;
            padding: 10px 0;
        }
        .chat .chat-title div {
            padding: 0 15px;
        }
        .chat .chat-title img {
            margin-left: 15px;
            border-radius: 50%;
            height: 60px;
            width: 60px;
        }
        .chat .chat-message .chat-history {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -moz-box-orient: vertical;
            -moz-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: start;
            -webkit-align-items: flex-start;
            -moz-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            padding: 15px 10px 15px 0;
            max-height: 70vh;
            overflow-y: auto;
            border-top: 2px solid #e1e1e1;
            border-bottom: 2px solid #e1e1e1;
        }
        .chat .chat-form {
            border-radius: 5px;
            margin-top: 10px;
            background-color: #fff;
            padding: 10px;
        }
        .chat .chat-form form .input-group,
        .chat .chat-form form{
            margin: 0;
        }
        .chat .chat-form span{
            cursor: pointer;
            -webkit-user-select: none;
        }
        .chat .chat-history .message-item {
            width: auto;
            max-width: 75%;
            margin-bottom: 20px;
        }

        .chat .chat-history .message-item .message-content {
            background: white;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
            padding: 10px 20px;
        }

        .chat .chat-history .message-item .message-action {
            color: #828282;
            margin-top: 5px;
            font-style: italic;
            font-size: 12px;
        }

        .chat .chat-history .message-item.outgoing-message {
            margin-left: auto;
        }

        .chat .chat-history .message-item.outgoing-message .message-content {
            background-color: #cdcdcd;
        }

        .chat .chat-history .message-item.outgoing-message .message-action {
            text-align: right;
        }
    </style>
@endpush

@push('js')
    <script src="{{asset('admin-vendor/plugins/autosize/autosize.js')}}"></script>
    <script>
        $(function () {
            $('.chat-history').scrollTop(10000);
            //Textarea auto growth
            autosize($('textarea.auto-growth'));
        });
    </script>
@endpush
