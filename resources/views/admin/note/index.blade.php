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
                                Список примечаний
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
                                        <th>Место публикации</th>
                                        <th>Значение</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($notes as $note)
                                        <tr>
                                            <td>{{$note->id}}</td>
                                            <td>
                                                <span>{{$note->noteable === 'article' ? 'Статьи и новости' : ''}}</span>
                                                <span>{{$note->noteable === 'playlist' ? 'Медитация' : ''}}</span>
                                                <span>{{$note->noteable === 'blog' ? 'Блог' : ''}}</span>
                                                <span>{{$note->noteable === 'event' ? 'События' : ''}}</span>
                                                <span>{{$note->noteable === 'tag' ? 'Теги' : ''}}</span>
                                                <span>{{$note->noteable === 'discount' ? 'Дисконт' : ''}}</span>
                                            </td>
                                            <td>{{$note->value}}</td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('notes.edit', $note)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('notes.destroy', $note)}}" method="POST" style="display:inline-block">
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
                            {{$notes->links()}}
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
                            <form action="{{route('notes.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="p-b-20">
                                    <label class="form-label">Место публикации</label>
                                    <select class="form-control show-tick" id="noteable" name="noteable">
                                        <option value="article">Статьи и новости</option>
                                        <option value="playlist">Медитация</option>
                                        <option value="blog">Блог</option>
                                        <option value="event">События</option>
                                        <option value="tag">Теги</option>
                                        <option value="discount">Дисконт</option>
                                    </select>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea required id="value" name="value" class="form-control" cols="30" rows="4" placeholder="Текст"></textarea>
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