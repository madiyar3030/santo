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
                                Список статей и новостей
                            </h2>
                            <button title="Добавить статью" type="button" data-toggle="modal" data-target="#defaultModal1" class="btn btn-danger waves-effect waves-float waves-effect m-t--30 m-r-60 pull-right">
                                Все статьи и новости для слайда
                            </button>
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
                                        <th>Фон. Картинка</th>
                                        <th>Поделиться pdf</th>
                                        <th>Автор</th>
                                        <th>Тип</th>
                                        <th>Заголовок</th>
                                        <th>Теги</th>
                                        <th>Дата публикации</th>
                                        <th>В слайде</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($articles as $article)
                                        <tr>
                                            <td>{{$article->id}}</td>
                                            <td><img src="{{asset($article->image)}}" alt="" style="max-width: 200px; max-height: 100px"></td>
                                            <td>
                                                {{!is_null($article->share_file_url) ? 'Есть' : 'Нет'}}
                                            </td>
                                            <td>{{$article->author->name}} <br> {{$article->author->last_name}}</td>
                                            <td>{{$article->type_name}}</td>
                                            <td>{{$article->title}}</td>
                                            <td>
                                                @foreach($article->tags as $tag)
                                                    {{$tag->title}}<br>
                                                @endforeach
                                            </td>
                                            <td>{{$article->published_at}}</td>
                                            <td>{{$article->show_main}}</td>
                                            <td>
                                                <form id="detailsForm_{{$article->id}}" action="{{route('details.index')}}">
                                                    <input type="hidden" name="type" value="article">
                                                    <input type="hidden" name="detailable_id" value="{{$article->id}}">
                                                    <a style="cursor: pointer;" onclick="document.getElementById('detailsForm_{{$article->id}}').submit()" >Детали</a>
                                                </form>
                                            </td>
                                            <td>
                                                <form action="{{route('articles.addShow', $article)}}" method="POST" style="display:inline-block">
                                                    @csrf
                                                    <button title="Добавить в карусель" type="submit" class="waves-effect btn btn-danger">
                                                        <i class="material-icons">queue</i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('articles.edit', $article)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('articles.destroy', $article)}}" method="POST" style="display:inline-block">
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
                            {{$articles->links()}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить статью или новость
                        </h5>
                        <div class="modal-body">
                            <form action="{{route('articles.store')}}" method="post" enctype="multipart/form-data">
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
                                    <label class="form-label">Тип</label>
                                    <select class="form-control show-tick" id="type" name="type">
                                        <option value="news">Новость</option>
                                        <option value="article">Статья</option>
                                    </select>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="datetime-local" class="form-control" id="published_at" name="published_at"/>
                                        <label class="form-label">Дата публикации</label>
                                    </div>
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
                                            <option value="{{$author->id}}">{{$author->name}} {{$author->last_name}}</option>
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
                                            <input type="file" name="thumb">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-20">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="title" name="title"/>
                                        <label class="form-label">Заголовок</label>
                                    </div>
                                </div>
                                <div id="tags">
                                    <select onchange="addTag(value)" class="form-control show-tick" id="tag">
                                        <option value="null">Выбирете нужный тег</option>
                                        @foreach ($tags as $tag)
                                            <option dataid="{{$tag->id}}" value="{{$tag->id}},{{$tag->title}}">{{$tag->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="userTags">
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




            <div class="modal fade" id="defaultModal1" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить новый тег
                        </h5>
                        <div class="modal-body">
                            {{csrf_field()}}
                            @foreach($slides as $slide)
                            <div>
                                <form action="{{route('articles.removeShow', $slide)}}" method="POST" style="display:inline-block">
                                    @csrf
                                    <span>{{$slide->title}}</span>
                                    <button type="submit" class="waves-effect btn btn-danger pull-right">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                            @endforeach
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">Отмена</button>
                            </div>
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
    <style>
        input, select{
            outline: none;
        }
    </style>

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