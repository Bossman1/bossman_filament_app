@foreach($model->relation_blocks as $relation)
    @includeIf($templatePath.'pages.content_object.relations.relation_blocks.'.$relationView.'.'.$relation->slug)
@endforeach

