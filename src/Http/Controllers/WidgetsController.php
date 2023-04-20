<?php

namespace BossmanFilamentApp\Http\Controllers;


use Illuminate\Database\Eloquent\Model;

class WidgetsController extends BaseController
{
    public static function getWidget($widget, Model $model)
    {

        if ($widget) {
            $widgetRelatedObjects = null;
            $widgetArchives = null;
            $widgetCalendarArchives = null;

            if($widget->key =='recent-posts'){
                $widgetRelatedObjects = ($model->parent) ? $model->widgetRelatedProducts(10, true, 3)->get() : null;
            }
            if($widget->key =='archives'){
                $widgetArchives = $model->widgetArchives(5);
            }
            if($widget->key =='archive-with-calendar'){
                $widgetCalendarArchives = $model->widgetCalendarArchives();
            }

            if (isset($widgetArchives->count) && $widgetArchives->count) {

                return view(self::templatePath().'includes.messages.exception.view_exception', ['errors' => $widgetArchives->errors]);
            }
            if (view()->exists(self::templatePath().'pages.content_object.widgets.' . $widget->key . '.widget')) {
                return view(self::templatePath().'pages.content_object.widgets.' . $widget->key . '.widget', [
                    'widget' => $widget,
                    'widgetRelatedObjects' => $widgetRelatedObjects,
                    'widgetArchives' => $widgetArchives,
                    'widgetCalendarArchives' => $widgetCalendarArchives,
                ])->render();
            }
        }
    }


    public static function getWidgetStyle($widget)
    {
        $html = '';
        if (view()->exists(self::templatePath().'pages.content_object.widgets.' . $widget->key . '.css.style')) {
            $html .= view(self::templatePath().'pages.content_object.widgets.archive-with-calendar.css.style',['widget'=>$widget])->render();
        }
        return $html;
    }


    public static function getWidgetScript($widget)
    {
        $html = '';
        if (view()->exists(self::templatePath().'pages.content_object.widgets.' . $widget->key . '.js.script')) {
            $html .= view(self::templatePath().'pages.content_object.widgets.archive-with-calendar.js.script')->render();
        }
        return $html;
    }



}
