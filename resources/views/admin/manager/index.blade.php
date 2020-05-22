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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-0">
                    @include('admin.components.error')
                    <div class="card">
                        <div class="header">
                            <h2>
                                Список администраторов
                            </h2>
                            <button type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--30 pull-right" >
                                <i class="material-icons m-t-5">add</i>
                            </button>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Имя</th>
                                        <th>Логин</th>
                                        <th>Пароль</th>
                                        <th>Роль</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        if(isset($_GET['page'])) {
                                            $i = ($_GET['page']-1) * 10 + 1;
                                        } else {
                                            $i = 1;
                                        }
                                    ?>
                                    @foreach($admins as $admin)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$admin->name}}</td>
                                            <td>{{$admin->username}}</td>
                                            <td>{{$admin->password}}</td>
                                            <td>{{$admin->role->title}}</td>
                                            <td>
                                                <a href="{{route('manager.edit', $admin->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                                <form action="{{route('manager.destroy', $admin->id)}}" method="POST" style="display:inline-block">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                            <h2>
                                Список ролей
                            </h2>
                            <button type="button" data-toggle="modal" data-target="#defaultModal2" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--30 pull-right" >
                                <i class="material-icons m-t-5">add</i>
                            </button>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Название</th>
                                        <th>Заказ</th>
                                        <th>Пользователи</th>
                                        <th>Чат</th>
                                        <th>Категории</th>
                                        <th>Товары</th>
                                        <th>Информации</th>
                                        <th>Администраторы</th>
                                        <th>Роли</th>
                                        <th>Списки</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        if(isset($_GET['page'])) {
                                            $i = ($_GET['page']-1) * 10 + 1;
                                        } else {
                                            $i = 1;
                                        }
                                    ?>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$role->title}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->orders)}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->users)}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->chats)}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->categories)}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->items)}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->general)}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->admin)}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->roles)}}</td>
                                            <td>{{\App\Http\Controllers\Admin\MainController::getText($role->lists)}}</td>
                                            <td>
                                                <a href="{{route('role.edit', $role->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                                <form action="{{route('role.destroy', $role->id)}}" method="POST" style="display:inline-block">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить администратора
                        </h5>
                        <form action="{{route('manager.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                @include('admin.components.input', ['label'=>'Имя','type'=>'text','name'=>'name','required'=>true])
                                @include('admin.components.input', ['label'=>'Логин','type'=>'text','name'=>'username','required'=>true])
                                @include('admin.components.input', ['label'=>'Пароль','type'=>'text','name'=>'password','required'=>true])
                                @include('admin.components.select', ['label'=>'Роль','name'=>'role_id','items'=>$roles,'title'=>'title','required'=>true])
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-link waves-effect">Добавить</button>
                                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Отмена</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="defaultModal2" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить роль
                        </h5>
                        <form action="{{route('role.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                @include('admin.components.input', ['label'=>'Название','type'=>'text','name'=>'title','required'=>true])
                                @include('admin.components.role', ['label' => 'Администраторы', 'name' => 'admin'])
                                @include('admin.components.role', ['label' => 'Роли', 'name' => 'roles'])
                                @include('admin.components.role', ['label' => 'Заказы', 'name' => 'orders'])
                                @include('admin.components.role', ['label' => 'Клиенты', 'name' => 'users'])
                                @include('admin.components.role', ['label' => 'Чаты', 'name' => 'chats'])
                                @include('admin.components.role', ['label' => 'Категории', 'name' => 'categories'])
                                @include('admin.components.role', ['label' => 'Товары', 'name' => 'items'])
                                @include('admin.components.role', ['label' => 'Общие', 'name' => 'general'])
                                @include('admin.components.role', ['label' => 'Списки', 'name' => 'lists'])
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-link waves-effect">Добавить</button>
                                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Отмена</button>
                            </div>
                        </form>
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