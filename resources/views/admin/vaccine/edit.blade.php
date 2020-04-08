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
                                Изменить вакцину
                            </h2>
                        </div>
{{--                        {{dd($vaccine)}}--}}
                        <div class="body">
                            <form action="{{route('vaccines.update', $vaccine)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="p-b-20">
                                        <p>Фоновая картинка</p>
                                        <img style="max-height: 200px; max-width: 200px" src="{{asset($vaccine->image)}}" alt="Картинка статьи"/>
                                    </div>
                                    <div @if(!is_null($vaccine->share_file_url)) class="p-b-20">
                                        <p>Поделиться pdf</p>
                                        <a target="_blank" href="{{$vaccine->share_file_url}}">Файл</a>
                                        @endif
                                    </div>
                                    <div id="titlediv" class="form-group form-float">
                                        <p>Изменить фоновую картинку (Необязательно)</p>
                                        <div class="fallback p-b-30">
                                            <input name="image" type="file" />
                                        </div>
                                        <p>Изменить pdf</p>
                                        <div class="fallback p-b-30">
                                            <input name="pdf" type="file" />
                                        </div>
                                        <div style="margin-top: 10px;" class="form-line">
                                            <input required type="text" class="form-control" id="title" name="title" value="{{$vaccine['title']}}"/>
                                            <label class="form-label">Заголовок</label>
                                        </div>
                                    </div>
                                    <div id="descriptiondiv" class="form-group form-float p-t-5">
                                        <div class="form-line">
                                            <textarea required rows="5" class="form-control" id="description" name="description">{{$vaccine['description']}}</textarea>
                                            <label class="form-label">Текст</label>
                                        </div>
                                    </div>
                                    <div id="age_from_div" class="form-group form-float p-t-5">
                                        <div class="form-line">
                                            <input type="number" step=0.1 type="number" class="form-control" id="age_from" name="age_from" value="{{$vaccine['age_from']}}"/>
                                            <label class="form-label">Возраст от</label>
                                        </div>
                                    </div>
                                    <div id="age_to_div" class="form-group form-float p-t-5">
                                        <div class="form-line">
                                            <input type="number" step=0.1 type="number" class="form-control" id="age_to" name="age_to" value="{{$vaccine['age_to']}}"/>
                                            <label class="form-label">Возраст до</label>
                                        </div>
                                    </div>
                                    <div id="age_type_div" class="p-b-20">
                                        <label class="form-label">Тип</label>
                                        <select class="form-control show-tick" id="age_type" name="age_type">
                                            <option value="year" {{$vaccine['age_type'] == 'year' ? 'selected' : ''}}>Год</option>
                                            <option value="month" {{$vaccine['age_type'] == 'month' ? 'selected' : ''}}>Месяц</option>
                                            <option value="week" {{$vaccine['age_type'] == 'week' ? 'selected' : ''}}>Неделя</option>
                                            <option value="day" {{$vaccine['age_type'] == 'day' ? 'selected' : ''}}>День</option>
                                        </select>
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
