@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Главная страница</h2>
            </div>

            <div class="row clearfix">
                @include('admin.components.error')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Изменить категорию
                            </h2>
                        </div>
                        <div class="body">
                            <form action="{{route('events.update', $event)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="p-b-20">
                                        <p>Фоновая картинка</p>
                                        <img style="max-height: 200px; max-width: 200px" src="{{asset($event->image)}}" alt="Картинка статьи"/>
                                    </div>
                                    <div @if(!is_null($event->share_file_url)) class="p-b-20">
                                        <p>Поделиться pdf</p>
                                        <a target="_blank" href="{{$event->share_file_url}}">Файл</a>
                                        @endif
                                    </div>
                                    <div class="p-b-20">
                                        <p>Изменить фоновую картинку (Необязательно)</p>
                                        <div class="fallback p-b-30">
                                            <input name="image" type="file" />
                                        </div>
                                        <p>Изменить pdf</p>
                                        <div class="fallback p-b-30">
                                            <input name="pdf" type="file" />
                                        </div>
                                        <label class="form-label">Тип</label>
                                        <select class="form-control show-tick" id="type" name="type">
                                            @foreach($types as $type)
                                                <option value="{{$type->id}}" {{$event['type_id'] == $type->id ? 'selected' : ''}}>{{$type->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input required type="text" class="form-control" id="title" name="title" value="{{$event['title']}}"/>
                                            <label class="form-label">Наименование</label>
                                        </div>
                                    </div>
                                    {{--<div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea required class="form-control" id="description" name="description">{{$event['description']}}</textarea>
                                            <label class="form-label">Описание</label>
                                        </div>
                                    </div>--}}
                                    <div class="form-group form-float p-t-5">
                                        <div class="form-line">
                                            <input required type="text" class="form-control" id="location" name="location" value="{{$event['location']}}"/>
                                            <label class="form-label">Место проведение событии</label>
                                        </div>
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
                                </div>
                                <div class="footer">
                                    <button type="submit" class="btn btn-link waves-effect">Изменить</button>
                                    <button type="button" onclick="location.href='{{URL::previous()}}'" class="btn btn-link waves-effect">Отмена</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
@endpush
