@extends('admin.layouts.app', ['title' => 'Редактировать товар', 'active_items' => 'active'])

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
                            <h2>Добавить товар</h2>
                        </div>
                        <div class="body">
                            @include('admin.components.error')
                            <label>Изображение</label>
                            <form action="{{route('image.store')}}" id="frmFileUpload" class="dropzone m-b-15" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="dz-message">
                                    <div class="drag-icon-cph">
                                        <i class="material-icons">touch_app</i>
                                    </div>
                                    <h3>Перетащите файлы сюда или нажмите, чтобы загрузить.</h3>
                                </div>
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>
                            <form id="main-form" method="POST" action="{{route('item.update', $item->id)}}">
                                @csrf
                                @method('put')
                                @include('admin.components.input', ['label'=>'Названия','type'=>'text','name'=>'title','required'=>true,'value'=>$item->title])
                                @include('admin.components.select', ['label'=>'Город','name'=>'city_id','items'=>$cities,'title'=>'city','required'=>true,'value'=>$item->city_id])
                                @include('admin.components.select', ['label'=>'Категория','name'=>'cat_id','items'=>$cats,'title'=>'title','required'=>true,'value'=>$item->cat_id])
                                @include('admin.components.select', ['label'=>'Подкатегория','name'=>'sub_cat_id','items'=>$subCats,'title'=>'title','value'=>$item->sub_cat_id])
                                @include('admin.components.input', ['label'=>'Цена','type'=>'number','name'=>'price','required'=>true,'value'=>$item->price])
                                @include('admin.components.input', ['label'=>'Количество','type'=>'number','name'=>'count','required'=>true,'value'=>$item->count])
                                @include('admin.components.input', ['label'=>'Бонус(%)','type'=>'number','name'=>'bonusPercentage','required'=>true,'value'=>$item->bonusPercentage])

                                @include('admin.components.checkbox', ['label'=>'Новинка','name'=>'isNew','checked'=>$item->isNew,'value'=>1])
                                @include('admin.components.checkbox', ['label'=>'Акция','name'=>'isDiscount','checked'=>$item->isDiscount,'value'=>1])


                                @include('admin.components.input', ['label'=>'Высота(см)','type'=>'number','name'=>'height','value'=>$item->height])
                                @include('admin.components.input', ['label'=>'Диаметер(см)','type'=>'number','name'=>'diameter','value'=>$item->diameter])
                                @include('admin.components.textarea', ['label'=>'Инфо','name'=>'description','value'=>$item->description])
                                <div class="form-group">
                                    <input type="checkbox" id="wine" onchange="chooseWine()" name="wine" {{$item->type == 'wine' ? 'checked' : ''}}/>
                                    <label for="wine">Вино</label>
                                </div>
                                <div id="wineBlock" style="display: {{$item->type == 'wine' ? 'block' : 'none'}};">
                                    @include('admin.components.select', ['label'=>'Страна','name'=>'country_id','items'=>$wineCountries,'title'=>'title','required'=>true])
                                    @include('admin.components.select', ['label'=>'Производитель','name'=>'manufacturer_id','items'=>$wineManufacturers,'title'=>'title','required'=>true])
                                    @include('admin.components.select', ['label'=>'Сорт','name'=>'class_id','items'=>$wineClasses,'title'=>'title','required'=>true])
                                    @include('admin.components.input', ['label'=>'Выдержка','type'=>'number','name'=>'age',])
                                    @include('admin.components.input', ['label'=>'Объем','type'=>'number','name'=>'volume',])
                                </div>
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
    <link href="{{asset('admin-vendor/plugins/dropzone/dropzone.css')}}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{asset('admin-vendor/plugins/dropzone/dropzone.js')}}"></script>
    <script src="{{asset('admin-vendor/plugins/jquery-validation/jquery.validate.js')}}"></script>
    <script>
        $(function () {
            Dropzone.options.frmFileUpload = {
                acceptedFiles: "image/jpeg, image/png, image/jpg",
                uploadMultiple: false,
                paramName: "image",
                maxFilesize: 20,
                method: 'POST',
                success: function (status, file) {
                    data = JSON.parse(status.xhr.response)
                    status.id = data.result
                    $("#main-form").append(`
                        <input type="hidden" name="images[]" value="`+data.result+`" />
                    `);
                    // $(".dz-preview:last-child").attr('data-id', data.result);
                },
                error: function (error) {
                    console.log(error)
                },
                addRemoveLinks: true,
                removedfile: function(file) {
                    var imageId = file.id
                    $("input[name='images[]'][value='"+imageId+"']").remove()
                    file.previewElement.remove();
                    $.ajax({
                        type: 'DELETE',
                        url: '{{route('image.destroy')}}',
                        data: {
                            id: imageId
                        },
                        success: function () {
                            console.log('success');
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });
                },
                init: function () {
                    var mockFiles = [

                        @foreach($item->images as $image)
                            @php
                                echo "
                                    {
                                        id: $image->id,
                                        name: '$image->path',
                                        size: 12345,
                                        type: 'image/png',
                                        path: '".asset($image->path)."'
                                    },
                                ";
                            @endphp
                        @endforeach
                    ]
                    for (var i = 0; i < mockFiles.length; i++  ) {
                        this.options.addedfile.call(this, mockFiles[i]);
                        this.options.thumbnail.call(this, mockFiles[i], mockFiles[i].path);
                        mockFiles[i].previewElement.classList.add('dz-success');
                        mockFiles[i].previewElement.classList.add('dz-complete');
                        console.log(mockFiles[i])
                    }
                }
            }
        });
    </script>
    <script>
        function chooseWine() {
            console.log($("#chooseWine").attr('checked'))
            $("#wineBlock").toggle()
        }
        $(function () {
            // $('#main-form').validate({
            //     rules: {
            //         'checkbox': {
            //             required: true
            //         },
            //         'gender': {
            //             required: true
            //         }
            //     },
            //     highlight: function (input) {
            //         $(input).parents('.form-line').addClass('error');
            //     },
            //     unhighlight: function (input) {
            //         $(input).parents('.form-line').removeClass('error');
            //     },
            //     errorPlacement: function (error, element) {
            //         $(element).parents('.form-group').append(error);
            //     }
            // });
        });
    </script>
@endpush
