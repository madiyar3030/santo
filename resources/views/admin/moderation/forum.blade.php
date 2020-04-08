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
                                Список форумов
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
                                        <th>Заголовок</th>
                                        <th>Автор</th>
                                        {{--                                        <th>Описание</th>--}}
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($forums as $forum)
                                        <tr>
                                            <td>{{$forum->id}}</td>
                                            <td> @if(count($forum->images) > 0)<img width="200" src="{{asset($forum->images[0]->url)}}" alt="">@endif</td>
                                            <td>{{$forum->title}}</td>
                                            <td>{{$forum->author['name']}} {{$forum->author['last_name']}}</td>
                                            <td style="min-width: 180px">
                                                <form action="{{route('forummods.destroy', $forum)}}" method="POST" style="display:inline-block">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="waves-effect btn btn-danger">
                                                        Отказать
                                                    </button>
                                                </form>
                                                <a href="{{route('forummods.edit', $forum)}}" class="waves-effect btn bg-deep-orange">
                                                    Изменить и Подтвердить
                                                </a>
                                                {{--<a href="{{route('eventmods.update', $event)}}" class="waves-effect btn bg-deep-orange">Подтвердить</a>--}}
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                            {{$forums->links()}}
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


    <script>
        function lol() {
            var form = document.getElementById('detailable_id');
            console.log(form.value);
        }
    </script>
@endpush