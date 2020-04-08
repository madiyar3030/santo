@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Детали статьи: {{$article->title}}</h2>
            </div>
            <div class="row clearfix">
                @include('admin.components.error')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">

                        </div>
                        <div class="body">
                            @foreach($article->details as $detail)
                                <div class="card">
                                    <div class="body">
                                        <div style="max-width: 70%; display: inline-block">
                                            @switch($detail->type)
                                                @case('title')
                                                <h4>{{$detail->value}}
                                                </h4>
                                                @break
                                                @case('description')
                                                <p>{{$detail->value}}
                                                </p>
                                                @break
                                                @case('image')
                                                <img style="max-height: 50%; max-width: 50%" src="{{asset($detail->value)}}">
                                                @break
                                                @case('citation')
                                                <div>
                                                    <blockquote>
                                                        <p><cite>{{$detail->value}}</cite>
                                                        </p>
                                                    </blockquote>
                                                </div>
                                                @break
                                            @endswitch
                                        </div>
                                        <div class="pull-right" style="display: inline-block">
                                            <a href="{{route('articles.edit', $article)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card">
                        <div class="body">

                        </div>
                    </div>
                    <div class="card">
                        <div class="header">
                        </div>
                        <div class="body">
                        <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Тип</th>
                                <th>Значение</th>
                                <th></th>
                            </tr>
                            </thead>
                        @include('admin.components.error')
                    @foreach($article->details as $detail)
                            <tbody>
                                <td>{{$detail->id}}</td>
                                <td>{{$detail->type}}</td>
                                <td>
                                    @switch($detail->type)
                                    @case('title')
                                    <h4>{{$detail->value}}
                                    </h4>
                                    @break
                                    @case('description')
                                    <p>{{$detail->value}}
                                    </p>
                                    @break
                                    @case('image')
                                    <img style="max-height: 500px; max-width: 500px" src="{{asset($detail->value)}}">
                                    @break
                                    @case('citation')
                                        <blockquote>
                                            <p><cite>{{$detail->value}}</cite>
                                            </p>
                                        </blockquote>
                                    @break
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{route('articles.edit', $article)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                </td>
                            </tbody>
                    @endforeach
                        </table>
                    </div>
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
@endpush