@extends('admin.layouts.app', ['title' => 'Категории', 'active_categories' => 'active'])

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
                    <a href="{{route('category.index')}}" class="btn btn-primary waves-effect">Назад</a><br><br>
                    <div class="card">
                        <div class="header">
                            <h2>
                                Список подкатегорий
                                <button type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right" >
                                    <i class="material-icons m-t-5">add</i>
                                </button>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Название</th>
                                        <th>Изображение</th>
                                        <th>Видимость</th>
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
                                    @foreach($cats as $item)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$item->title}}</td>
                                            <td><img class="img-responsive" src="{{asset($item->thumb)}}" alt="" style="max-height: 200px; max-width:200px"></td>
                                            <td>{{$item->hidden == 0 ? 'Скрыто' : 'Видно'}}</td>
                                            <td>
                                                <a href="{{route('subcategory.edit', $item->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                                <form action="{{route('subcategory.destroy', $item->id)}}" method="POST" style="display:inline-block">
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
                            {{$cats->links()}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить подкатегорию
                        </h5>
                        <form action="{{route('subcategory.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="cat_id" value="{{$cat_id}}">
                                @include('admin.components.input', ['label'=>'Название','type'=>'text','name'=>'title','required'=>true])
                                @include('admin.components.checkbox', ['label'=>'Видимость','name'=>'hidden','checked'=>true])
                                @include('admin.components.input', ['label'=>'','type'=>'file','name'=>'image'])
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
@endpush