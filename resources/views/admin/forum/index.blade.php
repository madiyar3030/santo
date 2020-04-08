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
                                Список форумов
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
                                        <th>Фон. Картинка</th>
                                        <th>Поделиться pdf</th>
                                        <th>Заголовок</th>
                                        <th>Автор</th>
                                        <th>Теги</th>
{{--                                        <th>Описание</th>--}}
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($forums as $forum)
                                        <tr>
                                            <td>{{$forum->id}}</td>
                                            <td><img src="{{asset($forum->image)}}" alt="" style="max-width: 200px; max-height: 100px"></td>
                                            <td>
                                                {{!is_null($forum->share_file_url) ? 'Есть' : 'Нет'}}
                                            </td>
                                            <td>{{$forum->title}}</td>
                                            <td>{{$forum->author['name']}} {{$forum->author['last_name']}}</td>
                                            <td>
                                                @foreach($forum->tags as $tag)
                                                    {{$tag->title}}<br>
                                                @endforeach
                                            </td>
                                            <td>
                                                <form id="detailsForm_{{$forum->id}}" action="{{route('details.index')}}">
                                                    <input type="hidden" name="detailable_id" value={{$forum->id}}>
                                                    <input type="hidden" name="type" value="forum">
                                                    <a style="cursor: pointer;" onclick="document.getElementById('detailsForm_{{$forum->id}}').submit()" >Детали</a>
                                                </form>
                                            </td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('forums.edit', $forum)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('forums.destroy', $forum)}}" method="POST" style="display:inline-block">
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
                            {{$forums->links()}}
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
                            <form action="{{route('forums.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="p-b-20">
                                    <p>Фоновая картинка (Необязательно)</p>
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
                                <div class="form-group form-float">
                                    <p class="form-label">Выбрать автора</p>
                                    <div class="form-line">
                                        <select name="author_id" id="">
                                            @foreach($authors as $author)
                                                <option value="{{$author->id}}">{{$author->name}}</option>
                                            @endforeach
                                        </select>
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
                                <div>
                                    <input required type="hidden" class="form-control" id="category_id" value="{{$cat_id}}" }} name="category_id"/>
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


    <script>
        function lol() {
            var form = document.getElementById('detailable_id');
            console.log(form.value);
        }
    </script>
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