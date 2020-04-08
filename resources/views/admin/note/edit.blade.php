@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
@section('content')
    <section class="content" xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Главная страница</h2>
            </div>

            <div class="row clearfix">
                @include('admin.components.error')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Изменить примечание
                            </h2>
                        </div>
                        <div class="body">
                            <form action="{{route('notes.update', $note)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="form-group form-float p-t-40 p-b-10">
                                        <label class="form-label">Заголовок</label>
                                        <div class="form-line">
                                            <select class="form-control show-tick" id="noteable" name="noteable">
                                                <option value="article" {{$note['noteable'] === 'article' ? 'selected' : ''}}>Статьи и новости</option>
                                                <option value="playlist" {{$note['noteable'] === 'playlist' ? 'selected' : ''}}>Медитация</option>
                                                <option value="blog" {{$note['noteable'] === 'blog' ? 'selected' : ''}}>Блог</option>
                                                <option value="event" {{$note['noteable'] === 'event' ? 'selected' : ''}}>События</option>
                                                <option value="tag" {{$note['noteable'] === 'tag' ? 'selected' : ''}}>Теги</option>
                                                <option value="discount" {{$note['noteable'] === 'discount' ? 'selected' : ''}}>Дисконт</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-float p-t-5">
                                        <label class="form-label">Текст</label>
                                        <div class="form-line">
                                            <textarea required cols="30" rows="5" class="form-control" id="value" name="value" placeholder="Текст">{{$note['value']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer">
                                    <button type="submit" class="btn btn-link waves-effect">Изменить</button>
                                    <button type="button" onclick="location.href='{{URL::previous()}}'" class="btn btn-link waves-effect">Отмена</button>
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