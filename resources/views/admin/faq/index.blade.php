@extends('admin.layouts.app', ['title' => 'Часто задаваемые вопросы', 'active_faq' => 'active'])

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
                                Часто задаваемые вопросы
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
                                        <th>Вопрос</th>
                                        <th>Ответ</th>
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
                                    @foreach($faq as $item)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$item->title}}</td>
                                            <td>{{$item->description}}</td>
                                            <td>
                                                <a href="{{route('faqs.edit', $item->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                                <form action="{{route('faqs.destroy', $item->id)}}" method="POST" style="display:inline-block">
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
                        <form action="{{route('faqs.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                @include('admin.components.input', ['label'=>'Вопрос','type'=>'text','name'=>'title','required'=>true])
                                @include('admin.components.textarea', ['label'=>'Ответ','name'=>'description','required'=>true])
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