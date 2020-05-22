@extends('admin.layouts.app', ['title' => $title, 'active_'.$type => 'active', 'active_list' => 'active'])

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
                                Лист
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
                                        <th>{{$title ?? null}}</th>
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
                                    @foreach($items as $item)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$item->title}}</td>
                                            <td>
                                                <a href="{{route('wine.edit', $item->id)}}?type={{$type}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                                <form action="{{route('wine.destroy', $item->id)}}" method="POST" style="display:inline-block">
                                                    @method('delete')
                                                    @csrf
                                                    <input type="hidden" name="type" value="{{$type}}">
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
                        <form action="{{route('wine.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="type" value="{{$type}}">
                                @include('admin.components.input', ['label'=>$title,'type'=>'text','name'=>'title','required'=>true])
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