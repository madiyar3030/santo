@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <div class="row clearfix">
                @include('admin.components.error')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form action="{{route('eventmods.update', $event)}}" method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="header">
                                <h2>Ответ</h2>
                                <button title="Добавить статью" type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-danger btn-circle waves-effect waves-circle waves-float waves-effect m-t--30 pull-right">
                                    <i class="material-icons m-t-5">add</i>
                                </button>
                            </div>
                            <div class="header">
                                {{csrf_field()}}
                                @method('PATCH')
                                <div class="p-b-20">
                                    <p>Фоновая картинка</p>
                                    @if(count($event->images) > 0)
                                        @foreach($event->images as $image)
                                            <img style="max-height: 200px; max-width: 200px" src="{{asset($image->url)}}" alt="Картинка статьи"/>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="p-b-20">
                                    <p>Изменить фоновую картинку (Необязательно)</p>
                                    <div class="fallback p-b-30">
                                        <input name="image" type="file" />
                                    </div>
                                    <label class="form-label">Тип</label>
                                    <select class="form-control show-tick" id="type" name="type">
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}" {{$event['type_id'] == $type->id ? 'selected' : ''}}>{{$type->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{substr($event['date_from'], 0, 10)}}"/>
                                        <label class="form-label">Дата началы проведение событии</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{substr($event['date_to'], 0, 10)}}"/>
                                        <label class="form-label">Дата окончание проведение событии</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="time_from" name="time_from" value="{{$event['time_from']}}"/>
                                        <label class="form-label">Время началы проведение событии</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="time_to" name="time_to" value="{{$event['time_to']}}"/>
                                        <label class="form-label">Время окончание проведение событии</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="title" name="title" value="{{$event['title']}}"/>
                                        <label class="form-label">Наименование</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea required class="form-control" id="description" name="description">{{$event['description']}}</textarea>
                                        <label class="form-label">Описание</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="location" name="location" value="{{$event['location']}}"/>
                                        <label class="form-label">Место проведение событии</label>
                                    </div>
                                </div>
                                <div class="footer">
                                    <button type="submit" class="btn btn-link waves-effect">Изменить</button>
                                    <button type="button" onclick="location.href='{{URL::previous()}}'" class="btn btn-link waves-effect">Отмена</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </section>
@endsection

@push('css')
    <!-- Bootstrap Select Css -->
    <link href="{{asset('admin-vendor/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
@endpush

@push('js')
    <!-- Select Plugin Js -->
    <script src="{{asset('admin-vendor/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

    <script>
        var typeSelect = document.getElementById('type');
        document.getElementById('image_input_div').hidden = true;
        typeSelect.onchange = function() {
            console.log(this.value);
            if (this.value === 'image') {
                document.getElementById('text_input').hidden = true;
                document.getElementById('image_input_div').hidden = false;
            }
            else {
                document.getElementById('text_input').hidden = false;
                document.getElementById('image_input_div').hidden = true;
            }
        }
        document.getElementById("author").addEventListener('change', function (e) {
            console.log("Changed to: " + e.target.value)
            if(e.target.value === 'old') {
                document.getElementById('author_id').style.display = 'block';
                document.getElementById('addAuthor').style.display = 'none';
            }
            else if(e.target.value === 'new') {
                document.getElementById('author_id').style.display = 'none';
                document.getElementById('addAuthor').style.display = 'block';
            }
        })
    </script>

    <script>
        var image_input = document.querySelector('#image_input');
        image_input.addEventListener('change', updateImageDisplay);

        function updateImageDisplay() {
            console.log(image_input);
            let files = image_input.files;
            let image = document.querySelector('#detail_image');
            image.src = window.URL.createObjectURL(files[0]);
        }
    </script>
@endpush