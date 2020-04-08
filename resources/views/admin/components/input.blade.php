<div class="form-group form-float">
    <div class="form-line">
        <input class="form-control" id="{{$name}}" name="{{$name}}" type="{{$type}}" {{isset($required) ? 'required' : ''}} value="{{old($name,isset($value) ? $value : '')}}"/>
        <label class="form-label" for="{{$name}}">{{$label}}</label>
    </div>
</div>
