@extends('admin.layouts.app', ['title' => 'Информация(оплата, доставка)', 'active_info' => 'active'])

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
                    <div class="card">
                        <div class="header">
                            <h2>
                                Информация(оплата, доставка)
                            </h2>
                        </div>
                        <div class="body">
                            @include('admin.components.error')
                            <form action="{{route('saveInfo')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @include('admin.components.textarea', ['label'=>'Оплата','name'=>$info[0]->key,'required'=>true,'value'=>$info[0]->description])
                                @include('admin.components.textarea', ['label'=>'Доставка','name'=>$info[1]->key,'required'=>true,'value'=>$info[1]->description])
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