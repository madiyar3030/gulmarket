@extends('admin.layouts.app', ['title' => 'Список заказов', 'active_orders' => 'active'])

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
                                Список заказов
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                        {{--<tr>--}}
                                        {{--<th>--}}
                                        {{--<form action="{{route('shipping.index')}}" method="get">--}}
                                        {{--@include('admin.components.select', ['name'=>'city_id','items'=>[],'label'=>'Выберите город','title'=>'city','etc'=>'onchange=this.form.submit()','value'=>request('city_id')])--}}
                                        {{--</form>--}}
                                        {{--</th>--}}
                                        {{--</tr>--}}
                                        <tr>
                                            <th>#</th>
                                            <th>Клиент</th>
                                            <th>Сумма</th>
                                            <th>Бонус для клиента</th>
                                            <th>Бонус от суммы</th>
                                            <th>Доставка</th>
                                            <th>Статус</th>
                                            <th>Дата доставки</th>
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
                                        @foreach($orders as $item)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$item->user->name}}</td>
                                                <td>{{$item->total}}</td>
                                                <td>{{$item->bonusUser}}</td>
                                                <td>{{$item->bonusPrice}}</td>
                                                <td>{{$item->shipping->title}} ({{$item->shipping->price.' тг'}})</td>
                                                <td>
                                                    @php
                                                        switch ($item->status) {
                                                            case 'waiting':
                                                                echo 'В ожидании';
                                                            break;
                                                            case 'accepted':
                                                                echo 'Принято';
                                                            break;
                                                            case 'declined':
                                                                echo 'Отказано';
                                                            break;
                                                            default:
                                                            break;
                                                        }
                                                    @endphp
                                                </td>
                                                <td>{{$item->orderDate}}</td>
                                                <td>
                                                    <a href="{{route('order.show', $item->id)}}" class="waves-effect btn btn-primary"><i class="material-icons">visibility</i></a>
                                                    <form action="{{route('order.destroy', $item->id)}}" method="POST" style="display:inline-block">
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
                            {{$orders->links()}}
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