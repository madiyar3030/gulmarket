@extends('admin.layouts.app', ['title' => 'Редактировать контакт', 'active_contacts' => 'active'])

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
                    <a href="{{route('contacts.index')}}" class="btn btn-primary waves-effect">Назад</a><br><br>
                    <div class="card">
                        <div class="header">
                            <h2>
                                Редактировать контакт
                            </h2>
                        </div>
                        <div class="body">
                            @include('admin.components.error')
                            <form action="{{route('contacts.update', $contact->id)}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <input type="hidden" name="lng" id="lng" value="{{$contact->lng}}">
                                <input type="hidden" name="lat" id="lat" value="{{$contact->lat}}">
                                @include('admin.components.select', ['label'=>'Город','name'=>'city_id','items'=>$cities,'title'=>'city','required'=>true,'value'=>$contact->city_id])
                                @include('admin.components.input', ['label'=>'Адрес','type'=>'text','name'=>'address','required'=>true,'value'=>$contact->address])
                                @include('admin.components.input', ['label'=>'График работы','type'=>'text','name'=>'workHour','required'=>true,'value'=>$contact->workHour])
                                @include('admin.components.tags_input', ['label'=>'Телефон','name'=>'phone','required'=>true,'value'=>$contact->phone])
                                <div class="col-sm-12 col-xs-12" id="map" style="height: 400px;"></div>
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
    <script src="https://api-maps.yandex.ru/2.1/?apikey=4c733a19-698f-41e6-82b1-81a884a2480c&lang=ru_RU" type="text/javascript">
    </script>
    <script type="text/javascript">
        ymaps.ready(initAdd);
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
            myGeoObject = new ymaps.GeoObject({
                // Описание геометрии.
                geometry: {
                    type: "Point",
                    coordinates: [{{$contact->lng}}, {{$contact->lat}}]
                },
                properties: {
                    iconContent: '',
                }
            }, {
                draggable: true
            })
            myGeoObject.events.add('dragend', function () {
                getAddress(myGeoObject.geometry.getCoordinates(), myGeoObject);
            });
            myMap.geoObjects.add(myGeoObject)
        }
        function getAddress(coords, myPlacemark) {
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