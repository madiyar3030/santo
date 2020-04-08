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
                                Список комментарии
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Статья</th>
                                        <th>Пользователь</th>
                                        <th>Комментарии</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
{{--                                    {{dd($comments)}}--}}
                                    @foreach($comments as $comment)
                                        <tr>
                                            <td>{{$comment->id}}</td>
                                            <td>{{trans('attributes.'.$comment->commentable_type)}}</td>
                                            <td>{{$comment->user->name ?? 'удален'}} {{$comment->user->last_name ?? ''}}</td>
                                            <td>{{$comment->comment}}</td>
                                            <td>
{{--                                                <form action="{{route('users.destroy', $comment->user->id)}}" method="POST" style="display:inline-block">--}}
{{--                                                    @method('delete')--}}
{{--                                                    @csrf--}}
{{--                                                    <button type="submit" class="waves-effect btn btn-danger">--}}
{{--                                                        Удалить пользователя--}}
{{--                                                    </button>--}}
{{--                                                </form>--}}
{{--                                                <form action="{{route('comments.edit', $comment)}}" method="POST" style="display:inline-block">--}}
{{--                                                    @method('delete')--}}
{{--                                                    @csrf--}}
{{--                                                    <a href="{{route('users.edit', $comment->user->id)}}" class="waves-effect btn {{$comment->user->blocked == 1 ? 'bg-pink' : 'bg-purple'}}"><i class="material-icons">{{$comment->user->blocked == 1 ? 'lock_open' : 'lock_outline'}}</i></a>--}}
{{--                                                </form>--}}
                                            </td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('comments.edit', $comment)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('comments.destroy', $comment)}}" method="POST" style="display:inline-block">
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
                            {{$comments->links()}}
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
