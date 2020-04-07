<div class="form-group">
    <input type="checkbox" id="{{$name}}" name="{{$name}}" value="{{isset($value) ? $value :''}}" {{$checked ? 'checked' : ''}} />
    <label for="{{$name}}">{{$label}}</label>
</div>
