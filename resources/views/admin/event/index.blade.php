@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
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
                                Список событии
                            </h2>
                            <a href="{{route('eventTypes.index')}}" class="waves-effect btn bg-deep-orange m-r-60 m-t--30 pull-right">Категории событии</a>
                            <button title="Добавить статью" type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-danger btn-circle waves-effect waves-circle waves-float waves-effect m-t--30 pull-right">
                                <i class="material-icons m-t-5">add</i>
                            </button>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Фон. Картинка</th>
                                        <th>Поделиться pdf</th>
                                        <th>Тип</th>
                                        <th>Заголовок</th>
                                        <th>Место</th>
                                        <th>Дата</th>
                                        <th>Время</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>{{$event->id}}</td>
                                            <td><img src="{{asset($event->image)}}" alt="" style="max-width: 200px; max-height: 100px"></td>
                                            <td>
                                                {{!is_null($event->share_file_url) ? 'Есть' : 'Нет'}}
                                            </td>
                                            <td>
{{--                                                <span> {{$event->type==="forum" ? 'Форум' : ""}}</span>--}}
{{--                                                <span> {{$event->type==="concert" ? 'Концерт' : ""}}</span>--}}
{{--                                                <span> {{$event->type==="conference" ? 'Конференция' : ""}}</span>--}}
{{--                                                <span> {{$event->type==="master-class" ? 'Мастер класс' : ""}}</span>--}}
{{--                                                <span> {{$event->type==="theater" ? 'Театр' : ""}}</span>--}}
{{--                                                <span> {{$event->type==="fair" ? 'Ярмарка' : ""}}</span>--}}
{{--                                                <span> {{$event->type==="holiday" ? 'Праздник' : ""}}</span>--}}
                                                <span>{{$event->event_title ?? ''}}</span>
                                            </td>
                                            <td>{{$event->title}}</td>
                                            <td>{{$event->location}}</td>
                                            <td>от {{substr($event->date_from, 0, 10)}}<br> до {{substr($event->date_to, 0, 10)}}</td>
                                            <td>от {{$event->time_from}}<br> до {{$event->time_to}}</td>
                                            <td>
                                                <form id="detailsForm_{{$event->id}}" action="{{route('details.index')}}">
                                                    <input type="hidden" name="type" value="event">
                                                    <input type="hidden" name="detailable_id" value="{{$event->id}}">
                                                    <a style="cursor: pointer;" onclick="document.getElementById('detailsForm_{{$event->id}}').submit()" >Детали</a>
                                                </form>
                                            </td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('events.edit', $event)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('events.destroy', $event)}}" method="POST" style="display:inline-block">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="waves-effect btn btn-danger">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            {{$events->links()}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить категорию
                        </h5>
                        <div class="modal-body">
                            <form action="{{route('events.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="p-b-20">
                                    <p>Изменить фоновую картинку (Необязательно)</p>
                                    <div class="fallback p-b-30">
                                        <input name="image" type="file" />
                                    </div>
                                    <p>Поделиться pdf</p>
                                    <div class="fallback p-b-30">
                                        <input name="pdf" type="file" />
                                    </div>
                                    <label class="form-label">Тип</label>
                                    <select class="form-control show-tick" id="type" name="type">
                                        @if (isset($types))
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}">{{$type->title}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="date" class="form-control" id="date_from" name="date_from"/>
                                        <label class="form-label">Дата началы событии</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="date" class="form-control" id="date_to" name="date_to"/>
                                        <label class="form-label">Дата окончание событии</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="time_from" name="time_from"/>
                                        <label class="form-label">Время началы событии</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="time_to" name="time_to"/>
                                        <label class="form-label">Время окончание событии</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="title" name="title"/>
                                        <label class="form-label">Заголовок</label>
                                    </div>
                                </div>
                                {{--<div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea required cols="40" class="form-control" name="description" id="description"></textarea>
                                        <label class="form-label">Текст</label>
                                    </div>
                                </div>--}}
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="location" name="location"/>
                                        <label class="form-label">Место событии</label>
                                    </div>
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
