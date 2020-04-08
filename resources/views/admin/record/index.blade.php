@extends('admin.layouts.app', ['title' => 'Главная страница', 'active_index' => 'active'])
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <div class="row clearfix">
                @include('admin.components.error')
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>Детали медитации</h2>
                            <button title="Добавить статью" type="button" data-toggle="modal" data-target="#defaultModal" class="btn btn-danger btn-circle waves-effect waves-circle waves-float waves-effect m-t--30 pull-right">
                                <i class="material-icons m-t-5">add</i>
                            </button>
                        </div>

                        <div class="body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Заголовок</th>
                                        <th>Картинка</th>
                                        <th>Музыка</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    @foreach($records as $record)
                                        <tbody>
                                        <td>{{$record->id}}</td>
                                        <td>{{$record->title}}</td>
                                        <td><img width="200" style="max-height: 200px;" src="{{asset($record->image)}}" alt=""></td>
                                        <td>
                                            <audio controls>
                                                <source src="{{asset($record->url)}}" type="audio/mpeg">
                                            </audio>
                                        </td>
                                        <td>
                                            <a style="margin-bottom: 5px" href="{{route('records.edit', $record)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                            <form action="{{route('records.destroy', $record)}}" method="POST" style="display:inline-block">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="waves-effect btn btn-danger">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </form>
                                        </td>
                                        </tbody>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <h5 class="modal-header">
                            Добавить медитацию
                        </h5>
                        <div class="modal-body">
                            <form action="{{route('records.store')}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="p-b-20">
                                    <p>Фоновая картинка</p>
                                    <div class="fallback p-b-30">
                                        <input name="image" type="file" />
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input type="hidden" class="form-control" id="playlist_id" name="playlist_id" value="{{$playlist_id}}"/>
                                        <input type="text" class="form-control" id="title" name="title" value=""/>
                                        <label class="form-label">Заголовок</label>
                                    </div>
                                </div>
                                <div class="p-b-20">
                                    <p>Музыка</p>
                                    <div class="fallback p-b-30">
                                        <input name="url" type="file" />
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