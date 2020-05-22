@extends('admin.layouts.app', ['title' => 'Администраторы', 'active_managers' => 'active'])

@section('content')
    <section class="content">
        <div class="container-fluid">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    <p>{{session()->get('message')}}</p>
                </div>
            @endif
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="{{route('manager.index')}}" class="btn btn-primary waves-effect">Назад</a><br><br>
                    <div class="card">
                        <div class="header">
                            <h2>
                                Редактировать роль
                            </h2>
                        </div>
                        <div class="body">
                            @include('admin.components.error')
                            <form action="{{route('role.update', $role->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                @include('admin.components.input', ['label'=>'Название','type'=>'text','name'=>'title','required'=>true,'value'=>$role->title])
                                @include('admin.components.role', ['label' => 'Администраторы', 'name' => 'admin', 'value' => $role->admin])
                                @include('admin.components.role', ['label' => 'Роли', 'name' => 'roles', 'value' => $role->roles])
                                @include('admin.components.role', ['label' => 'Заказы', 'name' => 'orders', 'value' => $role->orders])
                                @include('admin.components.role', ['label' => 'Клиенты', 'name' => 'users', 'value' => $role->users])
                                @include('admin.components.role', ['label' => 'Чаты', 'name' => 'chats', 'value' => $role->chats])
                                @include('admin.components.role', ['label' => 'Категории', 'name' => 'categories', 'value' => $role->categories])
                                @include('admin.components.role', ['label' => 'Товары', 'name' => 'items', 'value' => $role->items])
                                @include('admin.components.role', ['label' => 'Общие', 'name' => 'general', 'value' => $role->general])
                                @include('admin.components.role', ['label' => 'Списки', 'name' => 'lists', 'value' => $role->lists])
                                <button type="submit" class="btn btn-success waves-effect">Сохранить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(function () {
            $("input.role").click(function () {
                var checked = $(this)[0].checked;
                var name = $(this).attr('name');
                var value = parseInt($(this).val());
                var inputs = 'input.role[name="'+name+'"]';
                if (checked) {
                    switch (value) {
                        case 2:
                            $(inputs + '[value="'+1+'"]')[0].checked = true;
                            break;
                        case 4:
                            $(inputs + '[value="'+1+'"]')[0].checked = true;
                            $(inputs + '[value="'+2+'"]')[0].checked = true;
                            break;
                        case 8:
                            $(inputs + '[value="'+1+'"]')[0].checked = true;
                            $(inputs + '[value="'+2+'"]')[0].checked = true;
                            $(inputs + '[value="'+4+'"]')[0].checked = true;
                            break;
                    }
                } else {
                    switch (value) {
                        case 1:
                            $(inputs + '[value="'+8+'"]')[0].checked = false;
                            $(inputs + '[value="'+4+'"]')[0].checked = false;
                            $(inputs + '[value="'+2+'"]')[0].checked = false;
                            break;
                        case 2:
                            $(inputs + '[value="'+8+'"]')[0].checked = false;
                            $(inputs + '[value="'+4+'"]')[0].checked = false;
                            break;
                        case 4:
                            $(inputs + '[value="'+8+'"]')[0].checked = true;
                            break;
                    }
                }
            })
        })
    </script>
@endpush