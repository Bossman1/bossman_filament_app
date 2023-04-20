<form action="{{ route('content.form.submit') }}" method="post"  enctype="multipart/form-data">
    @csrf

    @if ($message = session('error'))


        <div class="alert alert-danger">
            {{ $message }}
            <div class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
        </div>

    @endif

    @if ($message = session('success'))

        <div class="alert alert-success">
            {{ $message }}
            <div type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
        </div>
    @endif
    @if ($message = session('info'))

        <div class="alert alert-info">
            {{ $message }}
            <div  class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </div>
        </div>
    @endif

    @if(isset($form->id))
        <input type="hidden" name="content_object_id" value="{{ $form->id }}">
    @endif

