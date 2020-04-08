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
                                Список Блог специалиста
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
                                        <th>Поделиться pdf</th>
                                        <th>Заголовок</th>
                                        <th>Автор</th>
                                        <th>Онлайн до(дата)</th>
                                        <th>С (время)</th>
                                        <th>До (время)</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($blogs as $note)
                                        <tr>
                                            <td>{{$note->id}}</td>
                                            <td><img src="{{asset($note->image)}}" alt="" style="max-width: 200px; max-height: 100px"></td>
                                            <td>
                                                {{!is_null($note->share_file_url) ? 'Есть' : 'Нет'}}
                                            </td>
                                            <td>{{$note->title}}</td>
                                            <td>{{$note->author->name}} {{$note->author->last_name}}</td>
                                            <td>{{$note->online_until}}</td>
                                            <td>{{$note->online_from}}</td>
                                            <td>{{$note->online_to}}</td>
                                            <td>
                                                <form id="detailsForm_{{$note->id}}" action="{{route('details.index')}}">
                                                    <input type="hidden" name="type" value="blog">
                                                    <input type="hidden" name="detailable_id" value="{{$note->id}}">
                                                    <a style="cursor: pointer;" onclick="document.getElementById('detailsForm_{{$note->id}}').submit()" >Детали</a>
                                                </form>
                                            </td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('blogs.edit', $note)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('blogs.destroy', $note)}}" method="POST" style="display:inline-block">
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
                            {{$blogs->links()}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить примечание
                        </h5>
                        <div class="modal-body">
                            <form action="{{route('blogs.store')}}" method="post" enctype="multipart/form-data">
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
                                </div>
                                <div id="authors" class="p-t-20">
                                    <select class="form-control show-tick" id="author_id" name="author_id">
                                        @foreach ($authors as $author)
                                            <option value="{{$author->id}}">{{$author->name}} {{$author->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group form-float p-t-20">
                                    <div class="form-line">
                                        <p>Онлайн до (дата)</p>
                                        <input required type="date" class="form-control" id="online_until" name="online_until"/>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-20">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="online_from" name="online_from"/>
                                        <label class="form-label">С (время)</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-20">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="online_to" name="online_to"/>
                                        <label class="form-label">До (время)</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-20">
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