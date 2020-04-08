<div class="form-group">
    <label for="{{$name}}">{{$label}}</label>
    <select id="{{$name}}" class="form-control show-tick" name="{{$name}}" {{isset($required)?'required':''}} {{isset($etc) ? $etc : ''}}>
        @foreach($items as $item)
            <option value="{{$item->id}}" {{((isset($value)&&$item->id==$value)||old($name)==$item->id)?'selected' : ''}}>{{$item->$title}}</option>
        @endforeach
    </select>
</div>
@push('css')
    <!-- Bootstrap Select Css -->
    <link href="{{asset('admin-vendor/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
@endpush

@push('js')
    <!-- Select Plugin Js -->
    <script src="{{asset('admin-vendor/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>
@endpush
