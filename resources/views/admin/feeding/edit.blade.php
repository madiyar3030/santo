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
                            <form action="{{route('feedings.update', $feeding)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @method('PATCH')
                                <input type="hidden" name="redirects_to" value="{{URL::previous()}}">
                                <div class="table-responsive">
                                    <div class="p-b-20">
                                        <p>Фоновая картинка</p>
                                        <img style="max-height: 200px; max-width: 200px" src="{{asset($feeding->image)}}" alt="Картинка статьи"/>
                                    </div>
                                    <div @if(!is_null($feeding->share_file_url)) class="p-b-20">
                                        <p>Поделиться pdf</p>
                                        <a target="_blank" href="{{$feeding->share_file_url}}">Файл</a>
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
                                        <label class="form-label">Тип</label>
                                        <select class="form-control show-tick" id="type" name="type">
                                            <option value="feeding" {{$feeding['type'] == 'feeding' ? 'selected' : ''}}>Грудное кормление</option>
                                            <option value="recipe" {{$feeding['type'] == 'recipe' ? 'selected' : ''}}>Рецепт</option>
                                            <option value="breastfeeding" {{$feeding['type'] == 'breastfeeding' ? 'selected' : ''}}>Искуственное кормление</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <input required type="text" class="form-control" id="title" name="title" value="{{$feeding['title']}}"/>
                                            <label class="form-label">Заголовок</label>
                                        </div>
                                    </div>
                                    <div class="form-group form-float">
                                        <div class="form-line">
                                            <textarea required class="form-control" id="description" name="description">{{$feeding['description']}}</textarea>
                                            <label class="form-label">Текст</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <label class="form-label">Номер категория</label>
                                        <select class="form-control" name="category_id" id="category_id">
                                            @foreach ($categories as $category)
                                                <option {{($feeding['category_id'] === $category->id) ? "selected" : ""}} value="{{$category->id}}">{{$category->title}}</option>
                                            @endforeach
                                            <option value="{{$category->id}}">fdhdjflldf</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="number" step=0.1 required type="number" step=0.1 value="{{$feeding['age_from']}}" type="number" class="form-control" id="age_from" name="age_from"/>
                                        <label class="form-label">Возраст от</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="number" step=0.1 type="number" step=0.1 value="{{$feeding['age_to']}}" type="number" class="form-control" id="age_to" name="age_to"/>
                                        <label class="form-label">Возрост до</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <label class="form-label">Тип</label>
                                    <select class="form-control show-tick" id="age_type" name="age_type">
                                        <option value="year" {{$feeding['age_type'] == 'year' ? 'selected' : ''}}>Год</option>
                                        <option value="month" {{$feeding['age_type'] == 'month' ? 'selected' : ''}}>Месяц</option>
                                        <option value="week" {{$feeding['age_type'] == 'week' ? 'selected' : ''}}>Неделя</option>
                                        <option value="day" {{$feeding['age_type'] == 'day' ? 'selected' : ''}}>День</option>
                                    </select>
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