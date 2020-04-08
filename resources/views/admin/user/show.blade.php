@extends('admin.layouts.app', ['title' => 'Пользователь '.$user->name, 'active_clients' => 'active'])

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
                    <a href="{{route('users.index')}}" class="btn btn-primary waves-effect">Назад</a><br><br>
                    <div class="card">
                        <div class="header">
                            <h2>О клиенте</h2>
                        </div>
                        <div class="body">
                            <div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#info" aria-controls="home" role="tab" data-toggle="tab">Информация</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="info">
                                        <form class="form-horizontal">
                                            <div style="text-align: center;">
                                                <img width="300" src="{{$user->thumb}}" alt="">
                                            </div>
                                            <div class="form-group">
                                                <label for="NameSurname" class="col-sm-2 control-label">Полное имя:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="NameSurname" value="{{$user->name}} {{$user->last_name}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Email" class="col-sm-2 control-label">Email:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <input type="text" class="form-control" id="Email"  value="{{$user->email}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Email" class="col-sm-2 control-label">Статус семьи:</label>
                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        @php
                                                            switch ($user->parent) {
                                                                case 'father':
                                                                    echo 'Папа' . (isset($childrens) ? ', '.count($childrens).' детей' : '');
                                                                    break;
                                                                case 'mother':
                                                                    echo 'Мама'. ($user->pregnant ? ', беременна' : '') . (isset($childrens) ? ', '.count($childrens).' детей' : '');
                                                                    break;
                                                                default:
                                                                    echo '-';
                                                                    break;
                                                            }
                                                        @endphp
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="description" class="col-sm-2 control-label">Информация</label>

                                                <div class="col-sm-10">
                                                    <div class="form-line">
                                                        <textarea class="form-control" id="description" rows="3" placeholder="Информация" readonly>{{$user->info}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label>
                                                    <a href="{{$user->vk}}">Vk</a>
                                                </label>
                                            </div>
                                            <div>
                                                <label>
                                                    <a href="{{$user->instagram}}">Instagram</a>
                                                </label>
                                            </div>
                                            <div>
                                                <label>
                                                    <a href="{{$user->facebook}}">Facebook</a>
                                                </label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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