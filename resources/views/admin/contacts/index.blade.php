@extends('admin.layouts.app', ['title' => 'Контакты', 'active_contacts' => 'active'])

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
                                Контакты
                                <button title="Добавить Контакт" type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right" >
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
                                            <th>
                                                <form action="{{route('contacts.index')}}" method="get">
                                                    @include('admin.components.select', ['name'=>'city_id','items'=>$cities,'label'=>'Выберите город','title'=>'city','etc'=>'onchange=this.form.submit()','value'=>request('city_id')])
                                                </form>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Город</th>
                                            <th>Адресс</th>
                                            <th>Телефон</th>
                                            <th>График работы</th>
                                            <th>Карта</th>
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
                                    @foreach($contacts as $item)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$item->city ? $item->city->city : ''}}</td>
                                            <td>{{$item->address}}</td>
                                            <td>{{$item->phone}}</td>
                                            <td>{{$item->workHour}}</td>
                                            {{--<td>{{$item->lat.'; '.$item->lng}}</td>--}}
                                            <td><div class="map" data-lat="{{$item->lat}}" data-lng="{{$item->lng}}" style="width: 200px; height: 200px"></div></td>
                                            <td>
                                                <a href="{{route('contacts.edit', $item->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                                <form action="{{route('contacts.destroy', $item->id)}}" method="POST" style="display:inline-block">
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
                        <form action="{{route('contacts.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="lng" id="lng">
                                <input type="hidden" name="lat" id="lat">
                                @include('admin.components.select', ['label'=>'Город','name'=>'city_id','items'=>$cities,'title'=>'city','required'=>true])
                                @include('admin.components.input', ['label'=>'Адрес','type'=>'text','name'=>'address','required'=>true])
                                @include('admin.components.input', ['label'=>'График работы','type'=>'text','name'=>'workHour','required'=>true])
                                @include('admin.components.tags_input', ['label'=>'Телефон','name'=>'phone','required'=>true])
                                <div class="col-sm-12 col-xs-12" id="map" style="height: 400px;"></div>
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
    <script src="https://api-maps.yandex.ru/2.1/?apikey=4c733a19-698f-41e6-82b1-81a884a2480c&lang=ru_RU" type="text/javascript">
    </script>
    <script type="text/javascript">
        ymaps.ready(init);
        ymaps.ready(initAdd);
        function init(){
            var maps = document.getElementsByClassName("map")
            for (i = 0; i < maps.length; i++) {
                var lng = maps[i].getAttribute("data-lng")
                var lat = maps[i].getAttribute("data-lat")
                console.log(lng, lat)
                myMap = new ymaps.Map(maps[i], {
                    center: [lng, lat],
                    zoom: 15
                });
                myGeoObject = new ymaps.GeoObject({
                    // Описание геометрии.
                    geometry: {
                        type: "Point",
                        coordinates: [lng, lat]
                    },
                    // Свойства.
                    properties: {
                        // Контент метки.
                        iconContent: '',
                    }
                })
                myMap.geoObjects.add(myGeoObject)
                myMap.controls.remove('geolocationControl');
                myMap.controls.remove('searchControl');
                myMap.controls.remove('trafficControl');
                myMap.controls.remove('typeSelector');
                myMap.controls.remove('fullscreenControl');
                myMap.controls.remove('rulerControl');
                myMap.behaviors.disable(['scrollZoom']);
            }
        }
        var myPlacemark
        function initAdd(){
            var map = document.getElementById("map")
            var myMap = new ymaps.Map(map, {
                center: [47.6543534,57.9328783],
                zoom: 5
            });
            myMap.controls.remove('geolocationControl');
            myMap.controls.remove('trafficControl');
            myMap.controls.remove('typeSelector');
            myMap.controls.remove('rulerControl');
            myMap.events.add('click', function (e) {
                var coords = e.get('coords');

                // Если метка уже создана – просто передвигаем ее.
                if (myPlacemark) {
                    myPlacemark.geometry.setCoordinates(coords);
                }
                // Если нет – создаем.
                else {
                    myPlacemark = createPlacemark(coords);
                    myMap.geoObjects.add(myPlacemark);
                    // Слушаем событие окончания перетаскивания на метке.
                    myPlacemark.events.add('dragend', function () {
                        getAddress(myPlacemark.geometry.getCoordinates());
                    });
                }
                getAddress(coords);
            });
        }
        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {
                iconCaption: 'поиск...'
            }, {
                preset: 'islands#violetDotIconWithCaption',
                draggable: true
            });
        }
        function getAddress(coords) {
            $("#lng").attr('value',coords[0])
            $("#lat").attr('value',coords[1])
            myPlacemark.properties.set('iconCaption', 'поиск...');
            ymaps.geocode(coords).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0);

                myPlacemark.properties
                    .set({
                        // Формируем строку с данными об объекте.
                        iconCaption: [
                            // Название населенного пункта или вышестоящее административно-территориальное образование.
                            firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                            // Получаем путь до топонима, если метод вернул null, запрашиваем наименование здания.
                            firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                        ].filter(Boolean).join(', '),
                        // В качестве контента балуна задаем строку с адресом объекта.
                        balloonContent: firstGeoObject.getAddressLine()
                    });
            });
        }
    </script>
@endpush