@extends('admin.layouts.app', ['title' => 'Редактировать контакт', 'active_contacts' => 'active'])

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
                    <a href="{{route('faqs.index')}}" class="btn btn-primary waves-effect">Назад</a><br><br>
                    <div class="card">
                        <div class="header">
                            <h2>
                                Редактировать контакт
                            </h2>
                        </div>
                        <div class="body">
                            @include('admin.components.error')
                            <form action="{{route('faqs.update', $faq->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                @include('admin.components.input', ['label'=>'Вопрос','type'=>'text','name'=>'title','required'=>true,'value'=>$faq->title])
                                @include('admin.components.textarea', ['label'=>'Ответ','name'=>'description','required'=>true,'value'=>$faq->description])
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