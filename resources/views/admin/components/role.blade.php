<h5>{{$label}}</h5>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="col-sm-3">
            <div class="demo-switch-title">ЧИТАТЬ</div>
            <div class="switch">
                <label><input type="checkbox" name="{{$name}}" value="1" class="role" {{isset($value) && ($value >= 1) ? 'checked' : ''}}><span class="lever switch-col-red"></span></label>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="demo-switch-title">СОЗДАТЬ</div>
            <div class="switch">
                <label><input type="checkbox" name="{{$name}}" value="2" class="role" {{isset($value) && ($value >= 2) ? 'checked' : ''}}><span class="lever switch-col-pink"></span></label>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="demo-switch-title">РЕДАКТИРОВАТЬ</div>
            <div class="switch">
                <label><input type="checkbox" name="{{$name}}" value="4" class="role" {{isset($value) && ($value >= 4) ? 'checked' : ''}}><span class="lever switch-col-purple"></span></label>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="demo-switch-title">УДАЛИТЬ</div>
            <div class="switch">
                <label><input type="checkbox" name="{{$name}}" value="8" class="role" {{isset($value) && ($value >= 8) ? 'checked' : ''}}><span class="lever switch-col-deep-purple"></span></label>
            </div>
        </div>
    </div>
</div>
