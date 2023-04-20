

@if(isset($argument->wrapper)) <div class="{{ $argument->wrapper }}"> @endif
    <button
        @if(isset($argument->class)) class="{{ $argument->class }}" @endif
        @if(isset($argument->id)) id="{{ $argument->id }}" @endif
        type="@if(isset($type)){{ $type }}@else submit @endif"
    >
     @if(isset($argument->value)) {{ $argument->value }}  @else {{ __('Submit') }} @endif
    </button>
    @if(isset($argument->wrapper)) </div> @endif
