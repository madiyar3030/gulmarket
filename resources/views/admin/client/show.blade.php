@extends('admin.layouts.app', ['title' => 'Пользователь '.$user->name, 'active_clients' => 'active'])

@section('content')
    <section class="content">
        <div class="container-fluid">
            @if (session()->has('message'))
                <div class="alert alert-success">
                    <p>{{session()->get('message')}}</p>
                </div>
            @endif
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12">
                    <a href="{{route('client.index')}}" class="btn btn-primary waves-effect">Назад</a><br><br>
                    <div class="card">
                        <div class="header">
                            <h2>О клиенте</h2>
                        </div>
                        <div class="body">
                            <div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#info" aria-controls="home" role="tab" data-toggle="tab">Информация</a></li>
                                    <li role="presentation"><a href="#history" aria-controls="settings" role="tab" data-toggle="tab">История заказов</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="info">
                                        <form class="form-horizontal">
                                            <div class="form-group">
                                                <label for="NameSurname" class="col-sm-2 control-label">Имя:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="NameSurname" value="{{$user->name}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="phone" class="col-sm-2 control-label">Телефон:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="phone"  value="{{$user->phone}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Email" class="col-sm-2 control-label">Email:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="Email"  value="{{$user->email}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="birth_date" class="col-sm-2 control-label">Дата рождения:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="date" class="form-control" id="birth_date" value="{{$user->birth_date}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="bonus" class="col-sm-2 control-label">Бонус:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="email" class="form-control" id="bonus" value="{{$user->bonus}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="description" class="col-sm-2 control-label">Информация</label>

                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <textarea class="form-control" id="description" rows="3" placeholder="Информация" readonly>{{$user->description}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($user->address))
                                                <div class="form-group">
                                                    <label for="city" class="col-sm-2 control-label">Город:</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-line">
                                                            <input type="email" class="form-control" id="city" value="{{\App\Models\City::find($user->address->city_id)->city}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="street" class="col-sm-2 control-label">Улица:</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-line">
                                                            <input type="email" class="form-control" id="street" value="{{$user->address->street}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="house" class="col-sm-2 control-label">Дом:</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-line">
                                                            <input type="email" class="form-control" id="house" value="{{$user->address->house}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="entrance" class="col-sm-2 control-label">Подъезд:</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-line">
                                                            <input type="email" class="form-control" id="entrance" value="{{$user->address->entrance}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="floor" class="col-sm-2 control-label">Этаж:</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-line">
                                                            <input type="email" class="form-control" id="floor" value="{{$user->address->floor}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="building" class="col-sm-2 control-label">Здания:</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-line">
                                                            <input type="email" class="form-control" id="building" value="{{$user->address->building}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="flat_number" class="col-sm-2 control-label">Квартира:</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-line">
                                                            <input type="email" class="form-control" id="flat_number" value="{{$user->address->flat_number}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="code" class="col-sm-2 control-label">Код от домофона:</label>
                                                    <div class="col-sm-10">
                                                        <div class="form-line">
                                                            <input type="email" class="form-control" id="code" value="{{$user->address->code}}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </form>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade in" id="history">
                                        <div class="panel panel-default panel-post">
                                            <div class="panel-body table-responsive">
                                                @include('admin.components.error')
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Общая сумма</th>
                                                            <th>Бонус(для пользователя)</th>
                                                            <th>Бонус(от покупок)</th>
                                                            <th>Метод платежа</th>
                                                            <th>Метод доставки</th>
                                                            <th>Цена доставки</th>
                                                            <th>Дата доставки</th>
                                                            <th>Статус</th>
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
                                                        @foreach($orders as $order)
                                                            <tr>
                                                                <td>{{$i++}}</td>
                                                                <td>{{$order->total}}</td>
                                                                <td>{{$order->bonusUser}}</td>
                                                                <td>{{$order->bonusPrice}}</td>
                                                                <td>{{$order->payType == 'cash' ? 'наличными' : 'безналичный'}}</td>
                                                                <td>{{$order->shipping->title}}</td>
                                                                <td>{{$order->shipping->price}}</td>
                                                                <td>{{$order->orderDate}}</td>
                                                                <td>
                                                                    @php
                                                                        switch ($order->status){
                                                                            case 'declined':
                                                                                echo '<span class="col-red">Отменено</span>';
                                                                                break;
                                                                            case 'waiting':
                                                                                echo '<span class="col-blue">Ожидает</span>';
                                                                                break;
                                                                            case 'accepted':
                                                                                echo '<span class="col-green">Успешно</span>';
                                                                                break;
                                                                        }
                                                                    @endphp
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