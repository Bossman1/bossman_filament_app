@if($model)
    <nav class="paging" aria-label="Page navigation example">
        {{ $model->links($templatePath.'vendor.pagination.bootstrap-4-new') }}
    </nav>
@endif

