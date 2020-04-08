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
                                Список медитации
                            </h2>
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
                                        <th>Картинка</th>
                                        <th>Заголовок</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($playlists as $playlist)
                                        <tr>
                                            <td>{{$playlist->id}}</td>
                                            <td><img src="{{asset($playlist->image)}}" alt="" style="max-width: 200px; max-height: 100px"></td>
                                            <td>{{$playlist->title}}</td>
                                            <td>
                                                <form id="detailsForm_{{$playlist->id}}" action="{{route('records.index')}}">
                                                    <input type="hidden" name="record_id" value="{{$playlist->id}}">
                                                    <a style="cursor: pointer;" onclick="document.getElementById('detailsForm_{{$playlist->id}}').submit()" >Детали</a>
                                                </form>
                                            </td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('playlists.edit', $playlist)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('playlists.destroy', $playlist)}}" method="POST" style="display:inline-block">
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
                            {{$playlists->links()}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить медитацию
                        </h5>
                        <div class="modal-body">
                            <form action="{{route('playlists.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="p-b-20">
                                    <p>Изменить фоновую картинку (Необязательно)</p>
                                    <div class="fallback p-b-30">
                                        <input name="image" type="file" />
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="title" name="title"/>
                                        <label class="form-label">Заголовок</label>
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