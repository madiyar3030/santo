@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
                <h2>Главная страница</h2>
            </div>

            <div class="row clearfix">
                @include('admin.components.error')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form></form>
                    <form action="{{route('forummods.update', $forum)}}" method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="header">
                                <h2>
                                    Изменить предложенные темы
                                </h2>
                            </div>
                            <div class="body">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="p-b-20">
                                        <p>Фоновая картинка</p>
                                        @if(count($forum->images) > 0)
                                            @foreach($forum->images as $image)
                                                <img style="max-height: 200px; max-width: 200px" src="{{asset($image->url)}}" alt="Картинка статьи"/>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="p-b-20">
                                        <p>Изменить фоновую картинку (Необязательно)</p>
                                        <div class="fallback p-b-30">
                                            <input name="image" type="file" />
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <p>Выберите категорию</p>
                                        <select name="cat_id">
                                            @foreach($cats as $cat)
                                                <option value="{{$cat->id}}">{{$cat->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input required type="text" class="form-control" id="title" name="title" value="{{$forum['title']}}"/>
                                            <label class="form-label">Заголовок</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea required type="text" class="form-control" id="description" name="description">{{$forum['description']}}</textarea>
                                            <label class="form-label">Текст</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <p>Автор</p>
                                    <select name="author_id" id="">
                                        @if (!is_null($ForumAuthor->user_id))
                                            <option value="{{$ForumAuthor->id}}">{{$ForumAuthor->fullname}}</option>
                                        @else
                                            @foreach($authors as $author)
                                                <option value="{{$author->id}}" {{$author->id === $forum->author_id ? 'selected' : ''}}>{{$author->name}} {{$author->last_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="footer">
                                    <button type="submit" class="btn btn-link waves-effect">Подтвердить</button>
                                    <button type="button" onclick="location.href='{{URL::previous()}}'" class="btn btn-link waves-effect">Отмена</button>
                                </div>
                            </div>
                        </div>
                    </form>
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