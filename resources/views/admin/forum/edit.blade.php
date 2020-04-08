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
                    <form action="{{route('forums.update', $forum)}}" method="post" enctype="multipart/form-data">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Изменить категорию
                            </h2>
                        </div>
                        <div class="body">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="p-b-20">
                                        <p>Фоновая картинка</p>
                                        <img style="max-height: 200px; max-width: 200px" src="{{asset($forum->image)}}" alt="Картинка статьи"/>
                                    </div>
                                    <div @if(!is_null($forum->share_file_url)) class="p-b-20">
                                        <p>Поделиться pdf</p>
                                        <a target="_blank" href="{{$forum->share_file_url}}">Файл</a>
                                        @endif
                                    </div>
                                    <div class="p-b-20">
                                        <p>Изменить фоновую картинку (Необязательно)</p>
                                        <div class="fallback p-b-30">
                                            <input name="image" type="file" />
                                        </div>
                                        <p>Изменить pdf</p>
                                        <div class="fallback p-b-30">
                                            <input name="pdf" type="file" />
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input required type="text" class="form-control" id="title" name="title" value="{{$forum['title']}}"/>
                                            <label class="form-label">Заголовок</label>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p>Изменить категорию</p>
                                    <select name="cat_id" id="cat_id">
                                        @foreach($cats as $cat)
                                            <option value="{{$cat->id}}" {{$cat->id === $forum->category_id ? 'selected' : ''}}>{{$cat->title}}</option>
                                        @endforeach
                                    </select>
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
                                <div class="form-group form-float p-t-5">
                                    <p>Все теги</p>
                                    <form>
                                    </form>
                                    @foreach($forum->tags as $tag)
                                        <div>{{$tag->title}}
                                            <form action="{{route('forums.removeTag')}}" method="POST" style="display:inline-block">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" name="tag_id" value="{{$tag->id}}">
                                                <input type="hidden" name="forum_id" value="{{$forum->id}}">
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
                                <div class="footer">
                                    <button type="submit" class="btn btn-link waves-effect">Изменить</button>
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
    <script>
        var userTags = [];
        var elem = document.getElementById('userTags');
        var hidd = document.getElementsByClassName('hiddenTag');
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