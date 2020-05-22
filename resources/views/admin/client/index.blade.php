@extends('admin.layouts.app', ['title' => 'Список пользователей', 'active_clients' => 'active'])

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
                                Список пользователей
                            </h2>

                            <form action="{{route('users.index')}}">
                                @csrf
                                <input type="search" name="search" value="{{$search ?? null}}">
                                <input type="submit" value="искать">
                            </form>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-basic-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Имя</th>
                                            <th>Email</th>
                                            <th>Телефон</th>
{{--                                            <th>Город</th>--}}
                                            <th>Бонус</th>
{{--                                            <th>Статус</th>--}}
                                            <th>Дата рождения</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                  @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->phone}}</td>
                                            <td>{{$user->bonus}}</td>
{{--                                            <td>{{$user->status}}</td>--}}
                                            <td>{{$user->birth_date}}</td>
                                        </tr>
                                  @endforeach
                                </table>
                                {{$users->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('css')
    <!-- JQuery DataTable Css -->
    <link href="{{asset('admin-vendor/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>
    <script>
        {{--$(function () {--}}
        {{--    $('.js-basic-example').DataTable({--}}
        {{--        language: {--}}
        {{--            "processing": "Подождите...",--}}
        {{--            "search": "Поиск:",--}}
        {{--            "lengthMenu": "Показать _MENU_ записей",--}}
        {{--            "info": "Записи с _START_ до _END_ из _TOTAL_ записей",--}}
        {{--            "infoEmpty": "Записи с 0 до 0 из 0 записей",--}}
        {{--            "infoFiltered": "(отфильтровано из _MAX_ записей)",--}}
        {{--            "infoPostFix": "",--}}
        {{--            "loadingRecords": "Загрузка записей...",--}}
        {{--            "zeroRecords": "Записи отсутствуют.",--}}
        {{--            "emptyTable": "В таблице отсутствуют данные",--}}
        {{--            "paginate": {--}}
        {{--                "first": "Первая",--}}
        {{--                "previous": "Предыдущая",--}}
        {{--                "next": "Следующая",--}}
        {{--                "last": "Последняя"--}}
        {{--            },--}}
        {{--            "aria": {--}}
        {{--                "sortAscending": ": активировать для сортировки столбца по возрастанию",--}}
        {{--                "sortDescending": ": активировать для сортировки столбца по убыванию"--}}
        {{--            }--}}
        {{--        },--}}
        {{--        responsive: true,--}}
        {{--        processing: true,--}}
        {{--        serverSide: true,--}}
        {{--        ajax: {--}}
        {{--            url: "{{ route('users.index') }}",--}}
        {{--            dataType: "json",--}}
        {{--            type: "GET",--}}
        {{--            data: { _token: "{{csrf_token()}}"}--}}
        {{--        },--}}
        {{--        columns: [--}}
        {{--            { data: 'id', name: 'id', 'visible': false},--}}
        {{--            { data: 'name', name: 'name' },--}}
        {{--            { data: 'email', name: 'email' },--}}
        {{--            { data: 'phone', name: 'phone' },--}}
        {{--            { data: 'address.city_id', name: 'city' },--}}
        {{--            { data: 'bonus', name: 'bonus' },--}}
        {{--            { data: 'blocked', name: 'blocked' },--}}
        {{--            { data: 'birth_date', name: 'birth_date' },--}}
        {{--            { data: 'action', name: 'action', orderable: false},--}}
        {{--        ]--}}
        {{--    });--}}

        {{--    //Exportable table--}}
        {{--    $('.js-exportable').DataTable({--}}
        {{--        dom: 'Bfrtip',--}}
        {{--        responsive: true,--}}
        {{--        buttons: [--}}
        {{--            'copy', 'csv', 'excel', 'pdf', 'print'--}}
        {{--        ]--}}
        {{--    });--}}
        {{--});--}}
    </script>
@endpush
