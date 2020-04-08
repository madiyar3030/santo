@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <div class="row clearfix">
                @include('admin.components.error')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <form action="{{route('consultations.update', $consultation)}}" method="post" enctype="multipart/form-data">
                        <div class="card">
                            <div class="header">
                                <h2>Ответ</h2>
                                <button title="Добавить статью" type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-danger btn-circle waves-effect waves-circle waves-float waves-effect m-t--30 pull-right">
                                    <i class="material-icons m-t-5">add</i>
                                </button>
                            </div>
                            <div class="header">
                                {{csrf_field()}}
                                @method('PATCH')
                                <div class="p-b-20">
                                    <p>Фоновая картинка</p>
                                    <img style="max-height: 200px; max-width: 200px" src="{{asset($consultation->image)}}" alt="Картинка статьи"/>
                                </div>
                                <div class="p-b-20 m-t-20">
                                    <p>Изменить фоновую картинку (Необязательно)</p>
                                    <div class="fallback p-b-30">
                                        <input name="image" type="file" />
                                    </div>
                                </div>
                                <h2>
                                    Автор ответа
                                    <select name="author" id="author">
                                        <option value="old">Выбрать из списка</option>
                                        <option value="new">Добавить автора</option>
                                    </select>
                                    <div style="margin-top: 20px;" id="author_id">
                                        <select  name="author_id">
                                            <option value="null">Никто не выбран</option>
                                            @foreach($authors as $author)
                                                <option {{$answer && $author->id === $answer->author_id ? 'selected' : ''}} value="{{$author->id}}">{{$author->name}} {{$author->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </h2>
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
                                <div class="m-t-40 form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" id="title" name="title" value="{{$consultation['title']}}"/>
                                        <label class="form-label">Заголовок вопроса</label>
                                    </div>
                                </div>


                                <div id="text_input" class="form-group form-float">
                                    <div class="form-line">
                                        <textarea name="description" id="description" class="form-control" cols="30" rows="4" placeholder="Вопрос">{{$consultation['description']}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Очередь</th>
                                            <th>Тип</th>
                                            <th>Значение</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        @if ($answer)
                                            @foreach($answer->details as $detail)
                                                <tbody>
                                                    <td>{{$loop->index+1}}</td>
                                                    <td>{{trans('attributes.'.$detail->type)}}</td>
                                                    <td>
                                                        @switch($detail->type)
                                                            @case('title')
                                                            <h4>{{$detail->value}}
                                                            </h4>
                                                            @break
                                                            @case('description')
                                                            <p>{{$detail->value}}
                                                            </p>
                                                            @break
                                                            @case('image')
                                                            <img style="max-height: 500px; max-width: 500px" src="{{asset($detail->value)}}">
                                                            @break
                                                            @case('citation')
                                                            <blockquote>
                                                                <p><cite>{{$detail->value}}</cite>
                                                                </p>
                                                            </blockquote>
                                                            @break
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        <form></form>
                                                        <a style="margin-bottom: 5px" href="{{route('details.edit', $detail)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                        <form action="{{route('details.destroy', $detail)}}" method="POST" style="display:inline-block">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit" class="waves-effect btn btn-danger">
                                                                <i class="material-icons">delete</i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tbody>


                                            @endforeach
                                        @endif
                                    </table>
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


            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить категорию
                        </h5>
                        <div class="modal-body">
                            <form action="{{route('details.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" name="detailable_id" value="{{$answer->id}}">
                                <input type="hidden" name="detailable_type" value="consultation_answer">
                                <div class="p-b-40">
                                    <label class="form-label">Тип</label>
                                    <select class="form-control show-tick" id="type" name="type">
                                        <option value="title">Заголовок</option>
                                        <option value="description">Текст</option>
                                        <option value="citation">Цитата</option>
                                        <option value="image">Картинка</option>
                                    </select>
                                </div>
                                <div id="text_input" class="form-group form-float">
                                    <div class="form-line">
                                        <textarea type="text" class="form-control" rows="3" id="text" name="text"></textarea>
                                        <label class="form-label">Текст</label>
                                    </div>
                                </div>
                                <div id="image_input_div">
                                    <img id="detail_image" style="max-width: 150px; max-height: 150px" src="">
                                    <p>Файл картинки (Обязательно)</p>
                                    <div class="fallback p-b-30">
                                        <input id="image_input" name="image" type="file" />
                                    </div>
                                </div>
                                {{--<div class="form-group form-float">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="order" name="order" value=""/>
                                        <label class="form-label">Очередь</label>
                                    </div>
                                </div>--}}
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
        var typeSelect = document.getElementById('type');
        document.getElementById('image_input_div').hidden = true;
        typeSelect.onchange = function() {
            console.log(this.value);
            if (this.value === 'image') {
                document.getElementById('text_input').hidden = true;
                document.getElementById('image_input_div').hidden = false;
            }
            else {
                document.getElementById('text_input').hidden = false;
                document.getElementById('image_input_div').hidden = true;
            }
        }
        document.getElementById("author").addEventListener('change', function (e) {
            console.log("Changed to: " + e.target.value)
            if(e.target.value === 'old') {
                document.getElementById('author_id').style.display = 'block';
                document.getElementById('addAuthor').style.display = 'none';
            }
            else if(e.target.value === 'new') {
                document.getElementById('author_id').style.display = 'none';
                document.getElementById('addAuthor').style.display = 'block';
            }
        })
    </script>

    <script>
        var image_input = document.querySelector('#image_input');
        image_input.addEventListener('change', updateImageDisplay);

        function updateImageDisplay() {
            console.log(image_input);
            let files = image_input.files;
            let image = document.querySelector('#detail_image');
            image.src = window.URL.createObjectURL(files[0]);
        }
    </script>
@endpush