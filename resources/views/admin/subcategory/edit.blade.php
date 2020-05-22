@extends('admin.layouts.app', ['title' => 'Редактировать подкатегорию', 'active_categories' => 'active'])

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
                    <a href="{{route('category.show', $cat->cat_id)}}" class="btn btn-primary waves-effect">Назад</a><br><br>
                    <div class="card">
                        <div class="header">
                            <h2>
                                Редактировать подкатегорию
                            </h2>
                        </div>
                        <div class="body">
                            @include('admin.components.error')
                            <form action="{{route('subcategory.update', $cat->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <input type="hidden" name="cat_id" value="{{$cat->cat_id}}">
                                @include('admin.components.input', ['label'=>'Названия','type'=>'text','name'=>'title','required'=>true,'value'=>$cat->title])
                                @include('admin.components.checkbox', ['label'=>'Видимость','name'=>'hidden','checked'=>$cat->hidden == 1 ? true : false])
                                @include('admin.components.file', ['name'=>'image','value'=>asset($cat->thumb)])
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
@endpush