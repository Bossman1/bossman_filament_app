@if(isset($argument->wrapper)) <div class="{{ $argument->wrapper }}"> @endif
    @if(!isset($argument->label)  ) <label for="{{ $key }}">{{ $form->label }} @if(isset($argument->required) || $options->required) <span class="form-required">*</span> @endif</label>@endif
    <input
        @if(isset($argument->class)) class="{{ $argument->class }}" @endif
        @if(isset($argument->id)) id="{{ $argument->id }}" @endif
        @if(isset($argument->rows)) rows="{{ $argument->rows }}" @endif
        @if(isset($argument->required) || $options->required) required="required" @endif
        @if(isset($attributes)) @foreach($attributes as $key =>  $attribute) {!! $key !!}="{!! $attribute !!}"  @endforeach @endif
        type="email"
        name="{{ $form->object_type_key }}___{{ $key }}"
        placeholder="{{ $form->value }}" />
    @if (isset($errors) && $errors->has($form->object_type_key.'___'.$key))
        <p class="help-block text-danger">{!! $errors->first($form->object_type_key.'___'.$key) !!}</p>
    @endif
@if(isset($argument->wrapper)) </div> @endif
