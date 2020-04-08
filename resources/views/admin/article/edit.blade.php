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
                    <div class="card">
                        <div class="header">
                            <h2>
                                Изменить категорию
                            </h2>
                        </div>
                        <div class="body">
                            <form></form>
                            <form action="{{route('articles.update', $article)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="p-b-20">
                                        <p>Фоновая картинка</p>
                                        <img style="max-height: 200px; max-width: 200px" src="{{asset($article->image)}}" alt="Картинка статьи"/>
                                    </div>
                                    <div @if(!is_null($article->share_file_url)) class="p-b-20">
                                        <p>Поделиться pdf</p>
                                        <a target="_blank" href="{{$article->share_file_url}}">Файл</a>
                                        @endif
                                    </div>
                                    <div class="p-b-20 m-t-20">
                                        <p>Изменить фоновую картинку (Необязательно)</p>
                                        <div class="fallback p-b-30">
                                            <input name="image" type="file" />
                                        </div>
                                        <p>Изменить pdf</p>
                                        <div class="fallback p-b-30">
                                            <input name="pdf" type="file" />
                                        </div>
                                        <label class="form-label">Тип</label>
                                        <select class="form-control show-tick" id="type" name="type">
                                            <option value="news" {{$article['type'] == 'news' ? 'selected' : ''}}>Новость</option>
                                            <option value="article" {{$article['type'] == 'article' ? 'selected' : ''}}>Статья</option>
                                        </select>
                                    </div>
                                    <div>
                                        <select class="form-control show-tick" id="type_author" name="type_author">
                                            <option value="1">Выбрать из списка</option>
                                            <option value="2">Добавить автора</option>
                                        </select>
                                    </div>
                                    <div id="authors" class="p-t-20">
                                        <select class="form-control show-tick" id="author_id" name="author_id">
                                            @foreach ($authors as $author)
                                                <option value="{{$author->id}}" {{$article->author->id == $author->id ? 'selected' : ''}}>{{$author->name}} {{$author->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="addAuthor" style="display: none;">
                                        <div class="form-group form-float p-t-20">
                                            <div class="form-line">
                                                <input type="text" class="form-control" id="name" name="name"/>
                                                <label class="form-label">Имя</label>
                                            </div>
                                        </div>
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <input type="text" class="form-control" id="last_name" name="last_name"/>
                                                <label class="form-label">Фамилия</label>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fallback p-b-30">
                                                <p>Добавить фото автора <small style="color: red;">рекомендуется</small></p>
                                                {{--                                            <input name="image" type="file"  id="thumb"/>--}}
                                                {{--                                            <input type="text" name="thumb">--}}
                                                <input type="file" name="thumb">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-float p-t-20">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="title" name="title" value="{{$article['title']}}"/>
                                            <label class="form-label">Заголовок</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float p-t-5">
                                        <div class="form-line">
                                            <input type="datetime-local" class="form-control" id="published_at" name="published_at" value="{{substr($article['published_at'], 0, 10)}}T{{substr($article['published_at'], 11, 12)}}"/>
                                            <label class="form-label">Дата публикации</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float p-t-5">
                                        <p>Все теги</p>
                                        <form>
                                        </form>
                                            @foreach($article->tags as $tag)
                                                <div style="margin-top: 10px;">{{$tag->title}}
                                                    <form action="{{route('articles.removeTag')}}" method="POST" style="display:inline-block">
                                                        @method('delete')
                                                        @csrf
                                                        <input type="hidden" name="tag_id" value="{{$tag->id}}">
                                                        <input type="hidden" name="article_id" value="{{$article->id}}">
                                                        {{--<button name="deletetag" value="{{$tag->pivot->taggable_id}}" style="margin-left: 50px;" type="submit" class="waves-effect btn btn-danger">
                                                            <i class="material-icons">delete</i>
                                                        </button>--}}
                                                        <button style="margin-left: 50px;" type="submit" class="waves-effect btn btn-danger">
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endforeach
                                    </div>
                                    <div id="tags">
                                        <p>Добавить тег</p>
                                        <select onchange="addTag(value)" class="form-control show-tick" id="tag">
                                            <option value="null">Выбирете нужный тег</option>
                                            @foreach ($tags as $tag)
                                                <option dataid="{{$tag->id}}" value="{{$tag->id}},{{$tag->title}}">{{$tag->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="userTags">
                                    </div>
                                    <button type="submit" class="btn btn-link waves-effect">Изменить</button>
                                    <button type="button" onclick="location.href='{{URL::previous()}}'" class="btn btn-link waves-effect">Отмена</button>
                                </div>
{{--                                <div class="footer">--}}
{{--                                </div>--}}
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
    <script>
        var userTags = [];
        var elem = document.getElementById('userTags');
        var hidd = document.getElementsByClassName('hiddenTag');
        document.getElementById("type_author").addEventListener('change', function (e) {
            console.log("Changed to: " + e.target.value)
            if(e.target.value === '1') {
                document.getElementById('authors').style.display = 'block';
                document.getElementById('addAuthor').style.display = 'none';
            }
            else if(e.target.value === '2') {
                document.getElementById('authors').style.display = 'none';
                document.getElementById('addAuthor').style.display = 'block';
            }
        })
        function tags() {
            elem.innerHTML = '';
            let first, second;
            for(var i=0; i<userTags.length; i++){
                first = userTags[i].split(',')[0];
                second = userTags[i].split(',')[1];
                elem.innerHTML += second +
                    '<span class="deleteTag" style="color: red; margin-left: 100px;" onclick="deleteTag(' + i + ')"> Удалить</span>' +
                    '<input class="hiddenTag" type="hidden" name="tag[]" value="">'

                    + '<br>';

                document.getElementsByClassName('hiddenTag')[i].value = first;
            }
        }
        function addTag(value) {
            // console.log(document.getElementById('tag').options[4].getAttribute('data-id'));
            console.log(value);
            userTags.push(value);
            tags();
        }
        function deleteTag(value) {
            userTags.splice(value, 1);
            tags();
        }
    </script>
@endpush