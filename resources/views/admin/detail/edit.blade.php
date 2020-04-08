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
                            <form action="{{route('details.update', $detail)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="p-b-40">
                                        <label class="form-label">Тип</label>
                                        <select class="form-control show-tick" id="type" name="type">
                                            <option value="title" {{$detail['type'] == 'title' ? 'selected' : ''}}>Заголовок</option>
                                            <option value="description" {{$detail['type'] == 'description' ? 'selected' : ''}}>Текст</option>
                                            <option value="citation" {{$detail['type'] == 'citation' ? 'selected' : ''}}>Цитата</option>
                                            <option value="image" {{$detail['type'] == 'image' ? 'selected' : ''}}>Картинка</option>
                                        </select>
                                    </div>
                                    <div id="text_input"  {{$detail['type'] != 'image' ? '' : 'hidden'}} class="form-group form-float">
                                        <div class="form-line">
                                            <textarea type="text" class="form-control" rows="3" id="text" name="text">{{$detail['type'] != 'image' ? $detail['value'] : ''}}</textarea>
                                            <label class="form-label">Текст</label>
                                        </div>
                                    </div>
                                    <div id="image_input_div" {{$detail['type'] != 'image' ? 'hidden' : ''}}>
                                        <img id="detail_image" style="max-width: 150px; max-height: 150px" src="{{asset($detail['value'])}}">
                                        <p>Файл картинки (Обязательно)</p>
                                        <div class="fallback p-b-30">
                                            <input id="image_input" name="image" type="file" />
                                        </div>
                                    </div>
                                    {{--<div class="form-group form-float">
                                        <div class="form-line">
                                            <input type="text" class="form-control" id="order" name="order" value="{{$detail['order']}}"/>
                                            <label class="form-label">Очередь</label>
                                        </div>
                                    </div>--}}
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

    <script>
        var typeSelect = document.getElementById('type');
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