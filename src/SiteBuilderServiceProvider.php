<?php

namespace BossmanFilamentApp;


use BossmanFilamentApp\Resources\ContentObjectResource;
use BossmanFilamentApp\Resources\FormContentObjectResource;
use BossmanFilamentApp\Resources\FormObjectResource;
use BossmanFilamentApp\Resources\FormObjectTypesResource;
use BossmanFilamentApp\Resources\LanguageResource;
use BossmanFilamentApp\Resources\LayoutResource;
use BossmanFilamentApp\Resources\MenuResource;
use BossmanFilamentApp\Resources\ObjectResource;
use BossmanFilamentApp\Resources\ObjectTypesResource;
use BossmanFilamentApp\Resources\RelationViewResource;
use BossmanFilamentApp\Resources\SidebarResource;
use BossmanFilamentApp\Resources\TemplateResource;
use BossmanFilamentApp\Resources\WidgetResource;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;


class SiteBuilderServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-site-builder-package';

    protected array $pages = [
//        Content::class
    ];

    public function __construct($app)
    {
        parent::__construct($app);
        $this->resources = [
            ObjectTypesResource::class,
            ObjectResource::class,
            ContentObjectResource::class,
            LanguageResource::class,
            MenuResource::class,
            TemplateResource::class,
            RelationViewResource::class,
            LayoutResource::class,
            SidebarResource::class,
            WidgetResource::class,
            FormObjectTypesResource::class,
            FormObjectResource::class,
            FormContentObjectResource::class,
        ];
    }

    public function configurePackage(Package $package): void
    {
        parent::configurePackage($package);

        $package
            ->name(static::$name)
            ->hasConfigFile(['filament-package-name-config', 'bossman_cms'])
            ->hasMigrations([
                '2020_02_04_000858_create_templates_table',
                '2023_02_22_154933_create_object_types_table',
                '2023_02_22_154934_create_objects_table',
                '2023_02_24_112251_create_content_objects_table',
                '2023_02_25_111340_create_content_object_pages_table',
                '2023_03_06_110037_create_custom_pages_table',
                '2023_03_09_121520_create_object_type_options_table',
                '2023_03_13_150425_create_languages_table',
                '2023_03_21_174543_content_object_blocks_table',
                '2023_03_25_091916_create_layouts_table',
                '2023_03_29_125325_create_sidebars_table',
                '2023_03_29_134543_create_widgets_table',
                '2023_03_29_134611_create_widget_sidebar_table',
            ])
            ->hasViews('views');


    }


    public function packageBooted(): void
    {
        parent::packageBooted();

        $this->publishes([
            __DIR__ . '/resources' => base_path('app/Filament/Resources/'),
        ], 'filament-package-name-resources');
//        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

//        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bossman_cms_views');
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/bossman_filament_app/'),
        ], 'bossman_cms_views');


//        $this->publishes([
//            __DIR__.'/../Models' => base_path('app/Models/'),
//        ], 'filament-package-name-models');

//
//        Livewire::component(Content::getName());
    }


}
