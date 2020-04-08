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
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Фон. Картинка</th>
                                        <th>Тип</th>
                                        <th>Заголовок</th>
                                        <th>Текст</th>
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
                                            <td> @if(count($event->images) > 0)<img width="200" src="{{asset($event->images[0]->url)}}" alt="">@endif</td>
                                            <td>{{$event->event_title ?? ''}}</td>
                                            <td>{{$event->title}}</td>
                                            <td style="max-width: 500px; word-break:break-word;">{{$event->description}}</td>
                                            <td>{{$event->location}}</td>
                                            <td style="width: 111px;">от {{substr($event->date_from, 0, 10)}}<br> до {{substr($event->date_to, 0, 10)}}</td>
                                            <td style="width: 75px;">от {{$event->time_from}}<br> до {{$event->time_to}}</td>
                                            <td style="min-width: 180px">
                                                <form action="{{route('eventmods.destroy', $event)}}" method="POST" style="display:inline-block">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="waves-effect btn btn-danger">
                                                        Отказать
                                                    </button>
                                                </form>
                                                <form action="{{route('eventmods.edit', $event)}}" method="GET" style="display:inline-block">
                                                    @method('PATCH')
                                                    @csrf
                                                    <button type="submit" class="waves-effect btn bg-deep-orange">
                                                        Изменить и Подтвердить
                                                    </button>
                                                </form>
                                                {{--<a href="{{route('eventmods.update', $event)}}" class="waves-effect btn bg-deep-orange">Подтвердить</a>--}}
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
