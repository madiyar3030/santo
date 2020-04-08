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
                                Список календаря развитии
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
                                        <th>Фон. картинка</th>
                                        <th>Поделиться pdf</th>
                                        <th>Заголовок</th>
                                        <th>Текст</th>
                                        <th>Возрост от</th>
                                        <th>Возрост до</th>
                                        <th>Тип</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($developments as $development)
                                        <tr>
                                            <td>{{$development->id}}</td>
                                            <td><img src="{{asset($development->thumb)}}" alt="" style="max-width: 200px; max-height: 100px"></td>
                                            <td>
                                                {{!is_null($development->share_file_url) ? 'Есть' : 'Нет'}}
                                            </td>
                                            <td>{{$development->title}}</td>
                                            <td>{{$development->description}}</td>
                                            <td>{{$development->age_from}}</td>
                                            <td>{{$development->age_to}}</td>
                                            <td>{{$development->age_type}}</td>
                                            <td>
                                                <form id="detailsForm_{{$development->id}}" action="{{route('details.index')}}">
                                                    <input type="hidden" name="type" value="development">
                                                    <input type="hidden" name="detailable_id" value="{{$development->id}}">
                                                    <a style="cursor: pointer;" onclick="document.getElementById('detailsForm_{{$development->id}}').submit()" >Детали</a>
                                                </form>
                                            </td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('developments.edit', $development)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('developments.destroy', $development)}}" method="POST" style="display:inline-block">
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
                            {{$developments->links()}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить прививку
                        </h5>
                        <div class="modal-body">
                            <form action="{{route('developments.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="p-t-20">
                                    <p>Изменить фоновую картинку</p>
                                    <div class="fallback p-b-30">
                                        <input name="image" type="file" />
                                    </div>
                                    <p>Поделиться pdf</p>
                                    <div class="fallback p-b-30">
                                        <input name="pdf" type="file" />
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="title" name="title"/>
                                        <label class="form-label">Заголовок</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <textarea required type="text" class="form-control" id="description" name="description"></textarea>
                                        <label class="form-label">Текст</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="number" step=0.1 class="form-control" id="age_from" name="age_from"/>
                                        <label class="form-label">Возраст от</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="number" step=0.1 class="form-control" id="age_to" name="age_to"/>
                                        <label class="form-label">Возраст до</label>
                                    </div>
                                </div>
                                <div class="p-b-20">
                                    <label class="form-label">Тип</label>
                                    <select class="form-control show-tick" id="type" name="age_type">
                                        <option value="year">Год</option>
                                        <option value="month">Месяц</option>
                                        <option value="week">Неделя</option>
                                        <option value="day">День</option>
                                    </select>
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
