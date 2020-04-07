@extends('admin.layouts.app', ['title' => 'Список товаров', 'active_items' => 'active'])

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
                                Список товаров
                            </h2>
                            <form action="{{route('item.index')}}" method="get">
                                @csrf
                                    <input type="search" name="search" value="{{$search}}">
                                <input type="submit" value="искать">
                            </form>
                            <a href="{{route('item.create')}}" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--30 pull-right" >
                                <i class="material-icons m-t-5">add</i>
                            </a>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>
                                                <form action="{{route('item.index')}}" method="get">
                                                    @if(request('sub_cat_id'))
                                                        <input type="hidden" name="sub_cat_id" value="{{request('sub_cat_id')}}">
                                                    @endif
                                                    @if(request('city_id'))
                                                        <input type="hidden" name="city_id" value="{{request('city_id')}}">
                                                    @endif
                                                        @if(request('page'))
                                                            <input type="hidden" name="page" value="{{request('page')}}">
                                                        @endif
                                                    @include('admin.components.select', ['name'=>'cat_id','items'=>$cats,'label'=>'Выберите категорию','title'=>'title','etc'=>'onchange=this.form.submit()','value'=>request('cat_id')])
                                                </form>
                                            </th>
                                            <th>
                                                <form action="{{route('item.index')}}" method="get">
                                                    @if(request('cat_id'))
                                                        <input type="hidden" name="cat_id" value="{{request('cat_id')}}">
                                                    @endif
                                                    @if(request('city_id'))
                                                        <input type="hidden" name="city_id" value="{{request('city_id')}}">
                                                    @endif
                                                        @if(request('page'))
                                                            <input type="hidden" name="page" value="{{request('page')}}">
                                                        @endif
                                                    @include('admin.components.select', ['name'=>'sub_cat_id','items'=>$subCats,'label'=>'Выберите подкатегорию','title'=>'title','etc'=>'onchange=this.form.submit()','value'=>request('sub_cat_id')])
                                                </form>
                                            </th>
                                            <th>
                                                <form action="{{route('item.index')}}" method="get">
                                                    @if(request('cat_id'))
                                                        <input type="hidden" name="cat_id" value="{{request('cat_id')}}">
                                                    @endif
                                                    @if(request('sub_cat_id'))
                                                        <input type="hidden" name="sub_cat_id" value="{{request('sub_cat_id')}}">
                                                    @endif
                                                    @if(request('page'))
                                                        <input type="hidden" name="page" value="{{request('page')}}">
                                                    @endif
                                                    @include('admin.components.select', ['name'=>'city_id','items'=>$cities,'label'=>'Выберите город','title'=>'city','etc'=>'onchange=this.form.submit()','value'=>request('city_id')])
                                                </form>
                                            </th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Название</th>
                                            <th>Категория</th>
                                            <th>Подкатегория</th>
                                            <th>Город</th>
                                            <th>Количество</th>
                                            <th>Цена</th>
                                            <th>Бонус(проценты)</th>
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
                                        @foreach($items as $item)

                                            <tr   {{!\App\Models\ItemImage::whereItemId($item->id)->exists() ? 'style=background:red;color:#fff':''}}>
                                                <td>{{$i++}}</td>
                                                <td>{{$item->title}}</td>
                                                <td>{{$item->cat ? $item->cat->title : ''}}</td>
                                                <td>{{$item->subCat ? $item->subCat->title : ''}}</td>
                                                <td>{{$item->city ? $item->city->city : ''}}</td>
                                                <td>{{$item->count}}</td>
                                                <td>{{$item->price}}</td>
                                                <td>{{$item->bonusPercentage}}</td>
                                                <td>
{{--                                                    <a href="{{route('item.show', $item->id)}}" class="waves-effect btn btn-primary"><i class="material-icons">visibility</i></a>--}}
                                                    <a href="{{route('item.edit', $item->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                                    <form action="{{route('item.destroy', $item->id)}}" method="POST" style="display:inline-block">
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
                            {{$items->appends(request()->all())->links()}}
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
