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
                                Изменить Блог специалиста
                            </h2>
                        </div>
                        <div class="body">
                            <form></form>
                            <form action="{{route('blogs.update', $blog)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="p-b-20">
                                        <p>Фоновая картинка</p>
                                        <img style="max-height: 200px; max-width: 200px" src="{{asset($blog->image)}}" alt="Картинка статьи"/>
                                    </div>
                                    <div @if(!is_null($blog->share_file_url)) class="p-b-20">
                                        <p>Поделиться pdf</p>
                                        <a target="_blank" href="{{$blog->share_file_url}}">Файл</a>
                                        @endif
                                    </div>
                                    <div class="p-b-20 m-t-20">
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
                                        <label class="form-label">Тип</label>
                                        <select class="form-control show-tick" id="author_id" name="author_id">
                                            @if (!is_null($ForumAuthor->user_id))
                                                <option value="{{$ForumAuthor->id}}">{{$ForumAuthor->fullname}}</option>
                                            @else
                                                @foreach($authors as $author)
                                                    <option value="{{$author->id}}" {{$author->id === $blog->author_id ? 'selected' : ''}}>{{$author->name}} {{$author->last_name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group form-float p-t-20">
                                        <div class="form-line">
                                            <p>Онлайн до (дата)</p>
                                            <input required type="date" class="form-control" id="online_until" name="online_until" value="{{$blog['online_until']}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group form-float p-t-20">
                                        <div class="form-line">
                                            <input required type="text" class="form-control" id="online_from" name="online_from" value="{{$blog['online_from']}}"/>
                                            <label class="form-label">С (время)</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float p-t-20">
                                        <div class="form-line">
                                            <input required type="text" class="form-control" id="online_to" name="online_to" value="{{$blog['online_to']}}"/>
                                            <label class="form-label">До (время)</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float p-t-20">
                                        <div class="form-line">
                                            <input required type="text" class="form-control" id="title" name="title" value="{{$blog['title']}}"/>
                                            <label class="form-label">Заголовок</label>
                                        </div>
                                    </div>
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