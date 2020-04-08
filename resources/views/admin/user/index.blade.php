@extends('admin.layouts.app', ['title' => 'Список пользователей', 'active_clients' => 'active'])

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
                                Список пользователей
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                        {{--<tr>--}}
                                            {{--<th>--}}
                                                {{--<form action="{{route('shipping.index')}}" method="get">--}}
                                                    {{--@include('admin.components.select', ['name'=>'city_id','items'=>[],'label'=>'Выберите город','title'=>'city','etc'=>'onchange=this.form.submit()','value'=>request('city_id')])--}}
                                                {{--</form>--}}
                                            {{--</th>--}}
                                        {{--</tr>--}}
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <form action="{{route('users.index')}}" method="GET" style="display:inline-block">
                                                    <button name="sort" value="name" type="submit" class="waves-effect btn btn-danger m-b-5">Имя от А</button>
                                                    <br>
                                                    <button name="sort" value="antname" type="submit" class="waves-effect btn btn-danger">Имя от Я</button>
                                                </form>
                                            </th>
                                            <th>Фамилия</th>
                                            <th>Email</th>
                                            <th>Телефон</th>
                                            <th>Статус семьи</th>
                                            <th>Дата рождения</th>
                                            <th>Аватар</th>
                                            <th>
                                                <form action="{{route('users.index')}}" method="GET" style="display:inline-block">
                                                    <button name="sort" value="created_at" type="submit" class="waves-effect btn btn-danger m-b-5">Дата регистрации &uarr;</button>
                                                    <br>
                                                    <button name="sort" value="ant_created_at" type="submit" class="waves-effect btn btn-danger">Дата регистрации &darr;</button>
                                                </form>
                                            </th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>{{$user->id}}</td>
                                                <td>{{$user->name}}</td>
                                                <td>{{$user->last_name}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->phone}}</td>
                                                <td>
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
                                                </td>
                                                <td>{{$user->birth_date}}</td>
                                                <td>
                                                    <img src="{{asset($user->thumb)}}" alt="" style="max-width: 200px; max-height: 100px">
                                                </td>
                                                <td>{{$user->created_at}}</td>
                                                <td>
                                                    <a href="{{route('users.show', $user->id)}}" class="waves-effect btn btn-primary"><i class="material-icons">visibility</i></a>
                                                    <form action="{{route('users.destroy', $user->id)}}" method="POST" style="display:inline-block">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></button>
                                                    </form>
                                                    <a href="{{route('users.edit', $user->id)}}" class="waves-effect btn {{$user->blocked == 1 ? 'bg-pink' : 'bg-purple'}}"><i class="material-icons">{{$user->blocked == 1 ? 'lock_open' : 'lock_outline'}}</i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{$users->links()}}
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