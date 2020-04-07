@extends('admin.layouts.app', ['title' => 'Доставка', 'active_shipping' => 'active'])

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
                                Доставка
                                <button title="Добавить доставку" type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right" >
                                    <i class="material-icons m-t-5">add</i>
                                </button>
                            </h2>
                            {{--<div class="col-xs-3 col-md-3 m-t--40 pull-right">--}}
                                {{--<form action="{{route('shipping.index')}}" method="get">--}}
                                    {{--@include('admin.components.select', ['name'=>'city_id','items'=>$cities,'label'=>'Выберите город','title'=>'city'])--}}
                                {{--</form>--}}
                            {{--</div>--}}
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <form action="{{route('shipping.index')}}" method="get">
                                                    @include('admin.components.select', ['name'=>'city_id','items'=>$cities,'label'=>'Выберите город','title'=>'city','etc'=>'onchange=this.form.submit()','value'=>request('city_id')])
                                                </form>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Город</th>
                                            <th>Условия</th>
                                            <th>Цена</th>
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
                                        @foreach($shipping as $item)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$item->city ? $item->city->city : ''}}</td>
                                                <td>{{$item->title}}</td>
                                                <td>{{$item->price}}</td>
                                                <td>
                                                    <a href="{{route('shipping.edit', $item->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                                    <form action="{{route('shipping.destroy', $item->id)}}" method="POST" style="display:inline-block">
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
                            Добавить
                        </h5>
                        <form action="{{route('shipping.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                @include('admin.components.select', ['label'=>'Город','name'=>'city_id','items'=>$cities,'title'=>'city','required'=>true])
                                @include('admin.components.input', ['label'=>'Условия','type'=>'text','name'=>'title','required'=>true])
                                @include('admin.components.input', ['label'=>'Цена','type'=>'number','name'=>'price','required'=>true])
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