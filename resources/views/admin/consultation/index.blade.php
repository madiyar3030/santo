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
                                Список вопросов к врачу
                            </h2>
{{--                            <button title="Добавить статью" type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-danger btn-circle waves-effect waves-circle waves-float waves-effect m-t--30 pull-right">--}}
{{--                                <i class="material-icons m-t-5">add</i>--}}
{{--                            </button>--}}
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                @include('admin.components.error')
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Картинка</th>
                                        <th>Заголовок вопроса</th>
                                        <th>Вопрос</th>
                                        <th>Автор вопрос</th>
                                        <th>Автор ответа</th>
                                        <th>Ответ</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($consultations as $consultation)
                                        <tr>
                                            <td>{{$consultation->id}}</td>
                                            <td><img width="200" src="{{$consultation->image}}" alt=""></td>
                                            <td>{{$consultation->title}}</td>
                                            <td style="max-width: 400px;word-break:break-word;">{{$consultation->description}}</td>
                                            <td>{{$consultation->author->name}} {{$consultation->author->last_name}}</td>
                                            <td>{{$consultation->answer["author"]['name']}} {{$consultation->answer["author"]['last_name']}}</td>
                                            <td>
                                                <form id="detailsForm_{{$consultation->answer->id}}" action="{{route('details.index')}}">
                                                    <input type="hidden" name="type" value="consultation_answer">
                                                    <input type="hidden" name="detailable_id" value="{{$consultation->answer->id}}">
                                                    <a class="waves-effect btn bg-deep-orange" style="cursor: pointer;" onclick="document.getElementById('detailsForm_{{$consultation->answer->id}}').submit()" >Ответ</a>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{route('consultations.edit', $consultation)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                            </td>
                                            <td style="min-width: 180px">
                                                <form action="{{route('consultations.destroy', $consultation)}}" method="POST" style="display:inline-block">
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
                            {{$consultations->links()}}
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
                            <form action="{{route('articles.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="p-b-20">
                                    <p>Изменить фоновую картинку (Необязательно)</p>
                                    <div class="fallback p-b-30">
                                        <input name="image" type="file" />
                                    </div>
                                    <label class="form-label">Тип</label>
                                    <select class="form-control show-tick" id="type" name="type">
                                        <option value="news">Новость</option>
                                        <option value="article">Статья</option>
                                    </select>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="title" name="title"/>
                                        <label class="form-label">Наименование</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="description" name="description"/>
                                        <label class="form-label">Описание</label>
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