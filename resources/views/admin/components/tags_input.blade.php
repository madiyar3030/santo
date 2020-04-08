<div class="form-group demo-tagsinput-area p-t-10">
    <div class="form-line">
        <label for="tagsinput">{{$label}}</label>
        <input type="text" class="form-control" data-role="tagsinput" id="tagsinput" name="{{$name}}" {{isset($required) ? 'required' : ''}} value="{{old($name,isset($value) ? $value : '')}}">
    </div>
</div>

@push('css')
    <link href="{{asset('admin-vendor/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{asset('admin-vendor/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
@endpush