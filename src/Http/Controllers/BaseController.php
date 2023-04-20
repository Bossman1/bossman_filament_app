<?php

namespace BossmanFilamentApp\Http\Controllers;

use App\Http\Controllers\Controller;
use BossmanFilamentApp\Models\ContentObject;
use BossmanFilamentApp\Models\Menu;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\EventListener\FragmentListener;

class BaseController extends Controller
{




    public  $templatePath;
    public static $tempName;

    public function __construct()
    {
        if (session()->get('locale') == null) {
            App::setLocale('ka');
            session()->put('locale', 'ka');
            $this->locale = 'ka';
        }

        $this->templatePath = 'bossmanView::templates.' . config('bossman_cms.template') . '.';


        $this->locale = session()->get('locale');

        $global_menu = ContentObject::with(['menuChildren', 'parent'])
            ->whereNull('content_object_id')
            ->whereNotNull('show_in_menu')
            ->where('show_in_menu', '!=', 0)
            ->published()
            ->orderBy('sort')
            ->get();

        $modelTopSliders = ContentObject::fetchBySlug('slider-1');

        $modelTopSocialBar = ContentObject::fetchBySlug('top-bar-social-icons-2');
        if (!$modelTopSocialBar) {
            abort(404);
        }

        $modelSubscribeBlock = ContentObject::fetchBySlug('subscribe-block');
        if (!$modelSubscribeBlock) {
            abort(404);
        }

        $modelSiteInformation = ContentObject::fetchBySlug('site-information');
        if (!$modelSiteInformation) {
            abort(404);
        }

        $modelTextBlock1 = ContentObject::fetchBySlug('we-understand-your-needs-on-construction');
        if (!$modelTextBlock1) {
            abort(404);
        }

        $modelOurValues = ContentObject::fetchBySlug('our-values');
        if (!$modelOurValues) {
            abort(404);
        }

        $modelAboutUs = ContentObject::fetchBySlug('about-us');
        if (!$modelAboutUs) {
            abort(404);
        }

        $modelHomePageCounter = ContentObject::fetchBySlug('home-page-counter');
        if (!$modelHomePageCounter) {
            abort(404);
        }
        $modelWhatWeDo = ContentObject::fetchBySlug('what-we-do');
        if (!$modelWhatWeDo) {
            abort(404);
        }

        $modelWorkOfExcellent = ContentObject::fetchBySlug('work-of-excellence');
        if (!$modelWorkOfExcellent) {
            abort(404);
        }

        $modelMenu1 = Menu::fetchByKey('recent-projects');
        if (!$modelMenu1) {
            abort(404);
        }

        $modelTestimonials = ContentObject::fetchBySlug('testimonials');
        if (!$modelTestimonials) {
            abort(404);
        }
        $modelhappyClients = ContentObject::fetchBySlug('happy-clients');
        if (!$modelhappyClients) {
            abort(404);
        }
        $modelProjects = ContentObject::fetchBySlug('projects');
        if (!$modelProjects) {
            abort(404);
        }


        View::share('templatePath', $this->templatePath);
        View::share('global_menu', $global_menu);
        View::share('modelTopSocialBar', $modelTopSocialBar);
        View::share('modelSiteInformation', $modelSiteInformation);
        View::share('modelTopSliders', $modelTopSliders);
        View::share('modelTextBlock1', $modelTextBlock1);
        View::share('modelOurValues', $modelOurValues);
        View::share('modelAboutUs', $modelAboutUs);
        View::share('modelHomePageCounter', $modelHomePageCounter);
        View::share('modelWhatWeDo', $modelWhatWeDo);
        View::share('modelWorkOfExcellent', $modelWorkOfExcellent);
        View::share('modelMenu1', $modelMenu1);
        View::share('modelTestimonials', $modelTestimonials);
        View::share('modelhappyClients', $modelhappyClients);
        View::share('modelProjects', $modelProjects);
        View::share('modelSubscribeBlock', $modelSubscribeBlock);
    }


    public static function templatePath(){

        return 'bossmanView::templates.' . config('bossman_cms.template') . '.';
    }

}
