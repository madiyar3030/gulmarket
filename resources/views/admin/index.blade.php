@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Главная страница</h2>
            </div>

            <div class="row clearfix">
                @include('admin.components.error')
                <div class="row clearfix">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a href="{{route('item.index')}}">
                            <div class="info-box bg-pink hover-expand-effect">
                                <div class="icon">
                                    <i class="material-icons">playlist_add_check</i>
                                </div>
                                <div class="content">
                                    <div class="text">ТОВАРЫ</div>
                                    <div class="number count-to" data-from="0" data-to="{{$count->items}}" data-speed="15" data-fresh-interval="20"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a href="{{route('order.index')}}">
                            <div class="info-box bg-cyan hover-expand-effect">
                                <div class="icon">
                                    <i class="material-icons">bookmark_order</i>
                                </div>
                                <div class="content">
                                    <div class="text">ЗАКАЗЫ</div>
                                    <div class="number count-to" data-from="0" data-to="{{$count->orders}}" data-speed="1000" data-fresh-interval="20"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a href="{{route('client.index')}}">
                            <div class="info-box bg-light-green hover-expand-effect">
                                <div class="icon">
                                    <i class="material-icons">person</i>
                                </div>
                                <div class="content">
                                    <div class="text">ПОЛЬЗОВАТЕЛИ</div>
                                    <div class="number count-to" data-from="0" data-to="{{$count->users}}" data-speed="1000" data-fresh-interval="20"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <a href="{{route('manager.index')}}">
                            <div class="info-box bg-orange hover-expand-effect">
                                <div class="icon">
                                    <i class="material-icons">person_add</i>
                                </div>
                                <div class="content">
                                    <div class="text">АДМИНИСТРАТОРЫ</div>
                                    <div class="number count-to" data-from="0" data-to="{{$count->managers}}" data-speed="1000" data-fresh-interval="20"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <a class="col-white" href="">
                            <div class="card">
                                <div class="body bg-pink">
                                    <div class="m-b--35 font-bold text-center">ПОЛЬЗОВАТЕЛИ</div>
                                    <ul class="dashboard-stat-list">
                                        <li>
                                            За сегодня
                                            <span class="pull-right"><b>{{number_format($users->today)}}</b></span>
                                        </li>
                                        <li>
                                            За неделю
                                            <span class="pull-right"><b>{{number_format($users->week)}}</b></span>
                                        </li>
                                        <li>
                                            За месяц
                                            <span class="pull-right"><b>{{number_format($users->month)}}</b></span>
                                        </li>
                                        <li>
                                            За все время
                                            <span class="pull-right"><b>{{number_format($users->all)}}</b></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <a class="col-white" href="">
                            <div class="card">
                                <div class="body bg-cyan">
                                    <div class="m-b--35 font-bold text-center">ТОВАРЫ</div>
                                    <ul class="dashboard-stat-list">
                                        <li>
                                            За сегодня
                                            <span class="pull-right"><b>{{number_format($items->today)}}</b></span>
                                        </li>
                                        <li>
                                            За неделю
                                            <span class="pull-right"><b>{{number_format($items->week)}}</b></span>
                                        </li>
                                        <li>
                                            За месяц
                                            <span class="pull-right"><b>{{number_format($items->month)}}</b></span>
                                        </li>
                                        <li>
                                            За все время
                                            <span class="pull-right"><b>{{number_format($items->all)}}</b></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                        <a class="col-white" href="">
                            <div class="card">
                                <div class="body bg-green">
                                    <div class="m-b--35 font-bold text-center">ЗАКАЗЫ</div>
                                    <ul class="dashboard-stat-list">
                                        <li>
                                            За сегодня
                                            <span class="pull-right"><b>{{number_format($orders->today)}}</b></span>
                                        </li>
                                        <li>
                                            За неделю
                                            <span class="pull-right"><b>{{number_format($orders->week)}}</b></span>
                                        </li>
                                        <li>
                                            За месяц
                                            <span class="pull-right"><b>{{number_format($orders->month)}}</b></span>
                                        </li>
                                        <li>
                                            За все время
                                            <span class="pull-right"><b>{{number_format($orders->all)}}</b></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
@endpush

@push('js')

    <!-- Jquery CountTo Plugin Js -->
    <script src="{{asset('admin-vendor/plugins/jquery-countto/jquery.countTo.js')}}"></script>
    {{--<script src="{{asset('admin-vendor/js/pages/index.js')}}"></script>--}}
    <script>
        $(function () {
            //Widgets count
            $('.count-to').countTo();
        });
    </script>
@endpush