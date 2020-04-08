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
                                Список кормлении
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
                                        <th>Тип</th>
                                        <th>Заголовок</th>
                                        <th>Текст</th>
                                        <th>Категория</th>
                                        <th>Возрост</th>
                                        <th>Тип возроста</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($feedings as $feeding)
                                        <tr>
                                            <td>{{$feeding->id}}</td>
                                            <td><img src="{{asset($feeding->image)}}" alt="" style="max-width: 200px; max-height: 100px"></td>
                                            <td>
                                                {{!is_null($feeding->share_file_url) ? 'Есть' : 'Нет'}}
                                            </td>
                                            <td>
                                                {{$feeding->type === 'feeding' ? 'Грудное кормление' : ''}}
                                                {{$feeding->type === 'recipe' ? 'Рецепт' : ''}}
                                                {{$feeding->type === 'breastfeeding' ? 'Искуственное кормление' : ''}}
                                            </td>
                                            <td>{{$feeding->title}}</td>
                                            <td>{{$feeding->description ?? ""}}</td>
                                            <td>{{$feeding->category->title ?? ''}}</td>
                                            <td>от {{$feeding->age_from}} до {{$feeding->age_to}}</td>
                                            <td>
                                                <span>{{$feeding->age_type === 'day' ? 'День' : ''}}</span>
                                                <span>{{$feeding->age_type === 'week' ? 'Неделя' : ''}}</span>
                                                <span>{{$feeding->age_type === 'month' ? 'Месяц' : ''}}</span>
                                                <span>{{$feeding->age_type === 'year' ? 'Год' : ''}}</span>
                                            </td>
                                            <td>
                                                <form id="detailsForm_{{$feeding->id}}" action="{{route('details.index')}}">
                                                    <input type="hidden" name="type" value="feeding">
                                                    <input type="hidden" name="detailable_id" value="{{$feeding->id}}">
                                                    <a style="cursor: pointer;" onclick="document.getElementById('detailsForm_{{$feeding->id}}').submit()" >Детали</a>
                                                </form>
                                            </td>
                                            <td style="min-width: 180px">
                                                <a href="{{route('feedings.edit', $feeding)}}" class="waves-effect btn bg-deep-orange"><i class="material-icons">edit</i></a>
                                                <form action="{{route('feedings.destroy', $feeding)}}" method="POST" style="display:inline-block">
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
                            {{$feedings->links()}}
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
                            <form action="{{route('feedings.store')}}" method="post" enctype="multipart/form-data">
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
                                        <option value="feeding">Грудное кормление</option>
                                        <option value="recipe">Рецепт</option>
                                        <option value="breastfeeding">Искуственное кормление</option>
                                    </select>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <input required type="text" class="form-control" id="title" name="title"/>
                                        <label class="form-label">Заголовок</label>
                                    </div>
                                </div>
                                <div class="form-group form-float">
                                    <div class="form-line">
                                        <textarea required class="form-control" id="description" name="description"></textarea>
                                        <label class="form-label">Текст</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <label class="form-label">Категория</label>
                                        <select class="form-control m-t-10" name="category" id="category">
                                            <option value="0">Выбрать из списка</option>
                                            <option value="1">Добавить категорию</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="category_id" class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <label class="form-label">Номер категория</label>
                                        <select class="form-control m-t-10" name="category_id">
                                            @foreach ($categories as $category)
                                            <option value="{{$category->id}}">{{$category->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div style="display: none;" id="new_cat" class="form-group form-float">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="new_cat"/>
                                        <label class="form-label">Название категории</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="number" step=0.1 required type="number" class="form-control" id="age_from" name="age_from"/>
                                        <label class="form-label">Возраст от</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <div class="form-line">
                                        <input type="number" step=0.1 type="number" class="form-control" id="age_to" name="age_to"/>
                                        <label class="form-label">Возрост до</label>
                                    </div>
                                </div>
                                <div class="form-group form-float p-t-5">
                                    <label class="form-label">Тип</label>
                                    <select class="form-control show-tick" id="age_type" name="age_type">
                                        <option value="year">Год</option>
                                        <option value="month">Месяц</option>
                                        <option value="week">Неделя</option>
                                        <option value="day">День</option>
                                    </select>
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

        document.getElementById("category").addEventListener('change', function (e) {
            console.log("Changed to: " + e.target.value)
            if(e.target.value === '0') {
                document.getElementById('category_id').style.display = 'block';
                document.getElementById('new_cat').style.display = 'none';
            }
            else if(e.target.value === '1') {
                document.getElementById('category_id').style.display = 'none';
                document.getElementById('new_cat').style.display = 'block';
            }
        })

    </script>
@endpush