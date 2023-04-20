<?php
namespace BossmanFilamentApp\Http\Controllers;

class MainController extends BaseController
{

    public function index()
    {
//        dd($this->templatePath.'index');
        return view($this->templatePath.'index');
    }


    public function view_category_posts(){
        return true;
    }

    public function contact()
    {
        return view($this->templatePath.'contact');
    }

}
