<?php

namespace BossmanFilamentApp\Http\Controllers;

use BossmanFilamentApp\Models\ContentObject;
use BossmanFilamentApp\Models\Layout;
use BossmanFilamentApp\Models\RelationView;
use BossmanFilamentApp\Models\Sidebar;
use BossmanFilamentApp\Models\Template;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;

class ContentObjectController extends BaseController
{
    private int $content_paginate;
    private string $listViewPath =  '';

    public function __construct()
    {
        parent:: __construct();
        $this->content_paginate = config('bossman_cms.php.paginate');
        $this->listViewPath = self::templatePath().'pages.content_object.list_view';

    }

    public function index()
    {
        return view('index');
    }


    public function archive($view, $parent_key, $date)
    {

        $detectDate =  explode('-',$date);
        if (count($detectDate) == 2){
            $date = Carbon::createFromFormat('m-Y', $date);
        }
        if (count($detectDate) == 3){
            $date = Carbon::createFromFormat('d-m-Y', $date);
        }


        $month = $date->format('m');
        $year = $date->format('Y');
        $day = $date->format('d');

        $modelParent = ContentObject::with(['parent', 'children', 'custom_page'])->where('slug', $parent_key)->first();

        $query =  $modelParent->children();
        $query->whereYear('created_at', $year);
        $query->whereMonth('created_at', $month);
        if (count($detectDate) == 3){
            $query->whereDay('created_at', $day);
        }

        $children =  $query->paginate($this->content_paginate);

        $viewPath = 'pages.content_object.list_view';
        $viewFile = $this->listViewPath . '.' . $view;
        if (!view()->exists($viewFile)) {

            return view(self::templatePath().'includes.messages.exception.view_exception', ['message' => 'The View <b>' . $view . '.blade.php</b> Not exitst in the path: <b>' . $viewPath . '</b>']);
        }

        return view(self::templatePath().'pages.content_object.list_view.' . $view, [
            'model' => $modelParent,
            'children' => $children,
            'sidebar' => $this->getSidebar($modelParent)
        ]);
    }

    public function list($slug)
    {
        $model = ContentObject::fetchBySlug($slug);
        $template = Template::select('slug')->where('id', $model->template_id)->first();
        $templateName = $template ? $template->slug : "default";
        $viewFile = $this->listViewPath . '.' . $templateName;
        $viewPath = 'default.pages.content_object.list_view';
        if (!view()->exists($viewFile)) {

            return view(self::templatePath().'includes.messages.exception.view_exception', ['message' => 'The View <b>' . $templateName . '.blade.php</b> Not exitst in the path: <b>' . $viewPath . '</b>']);
        }

        return view($viewFile, [
            'model' => $model,
            'sidebar' => $this->getSidebar($model),
            'children' => $model->children()->paginate($this->content_paginate),
        ]);


    }


    private function getSidebar($model)
    {
        $sidebarKey = null;
        $sidebarModel = null;
        if ($model->parent && $model->parent->sidebar_id != null) {
            $sidebarModel = Sidebar::with('widgets')->where('id', $model->parent->sidebar_id)->first();
            $sidebarKey = $sidebarModel->key;
        }

        if ($model->sidebar_id != null) {
            $sidebarModel = Sidebar::with('widgets')->where('id', $model->sidebar_id)->first();
            $sidebarKey = $sidebarModel->key;
        }

        if ($sidebarKey != null && !view()->exists(self::templatePath().'.pages.content_object.sidebar.' . $sidebarKey)) {

            return view(self::templatePath().'includes.messages.exception.view_exception', ['message' => 'The Sidebar file <b>' . $sidebarKey . '.blade.php</b> Not exitst in the path: <b>pages.content_object.sidebar' . '</b>']);
        }
        $sidebar = collect();
        $sidebar->key = $sidebarKey;
        $sidebar->widgets = $sidebarModel->widgets ?? '';

        return $sidebar;

    }


    public function view($slug)
    {
        $model = ContentObject::fetchBySlug($slug);

        if (!isset($model->id)) {

            return view(self::templatePath().'includes.messages.exception.view_exception', ['message' => 'The Page not found']);
        }

        $template = Template::select('slug')->where('id', $model->template_id)->first();
        $relation = RelationView::select('slug')->where('id', $model->relation_view_id)->first();
        $layout = Layout::select('slug')->where('id', $model->layout_id)->first();

        $templateName = $template ? $template->slug : 'default';
        $relationView = $relation ? $relation->slug : 'default';
        $layoutView = $layout ? $layout->slug : 'main';
        $viewPath = self::templatePath().'pages.content_object';
        $viewRelationPath = self::templatePath().'pages.content_object.relations';

        $viewFile = $viewPath . '.' . $templateName;
        $viewRelationFile = $viewRelationPath . '.' . $relationView;
        $viewLayoutFile = self::templatePath().'layouts.' . $layoutView;


        if (!view()->exists($viewLayoutFile)) {
            return view(self::templatePath().'includes.messages.exception.view_exception', ['message' => 'The Layout <b>' . $layoutView . '.blade.php</b> Not exitst in the path: <b>' . $viewLayoutFile . '</b>']);
        }

        if (!view()->exists($viewFile)) {
            return view(self::templatePath().'includes.messages.exception.view_exception', ['message' => 'The View <b>' . $templateName . '.blade.php</b> Not exitst in the path: <b>' . $viewPath . '</b>']);
        }
//        if(!view()->exists($viewRelationFile) && $model->relation_view_id){
//            return view('includes.messages.exception.view_exception', ['message' => 'The Relation View <b>'. $relationView. '.blade.php</b> Not exitst in the path: <b>'. $viewRelationPath.'</b>']);
//        }

//        return view($viewPath.'.' . $templateName, ['model' => $model,'children' => $model->children,'relationView' => $relationView]);

        return View::make(self::templatePath().'layouts.' . $layoutView, [
            'model' => $model,
        ])
            ->nest('content', $viewPath . '.' . $templateName, [
                'model' => $model,
                'children' => $model->children,
                'relationView' => $relationView,
                'sidebar' => $this->getSidebar($model),
            ]);


    }


}
