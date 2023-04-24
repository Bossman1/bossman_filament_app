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
    public static string $name = 'bossman-filament-package';

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
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $package
            ->name(static::$name)
            ->hasConfigFile(['bossman_cms'])
//            ->hasMigrations([
//                '2020_02_04_000858_create_templates_table',
//                '2023_02_22_154933_create_object_types_table',
//                '2023_02_22_154934_create_objects_table',
//                '2023_02_24_112251_create_content_objects_table',
//                '2023_02_25_111340_create_content_object_pages_table',
//                '2023_03_06_110037_create_custom_pages_table',
//                '2023_03_09_121520_create_object_type_options_table',
//                '2023_03_13_150425_create_languages_table',
//                '2023_03_21_174543_content_object_blocks_table',
//                '2023_03_25_091916_create_layouts_table',
//                '2023_03_29_125325_create_sidebars_table',
//                '2023_03_29_134611_create_sidebar_widget_table',
//                '2023_03_29_134543_create_widgets_table',
//                '2023_03_29_134611_create_sidebar_widget_table',
//                '2023_03_30_15000_create_form_objects_table',
//                '2023_03_30_15001_create_form_object_types_table',
//                '2023_03_30_15002_create_form_custom_pages_table',
//                '2023_03_30_15003_create_form_content_objects_table',
//                '2023_03_08_153118_create_media_table',
//            ])
            ->hasViews('views');


    }


    public function packageBooted(): void
    {
        parent::packageBooted();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bossmanView');
//        $this->loadViewsFrom(__DIR__.'/../database/migrations', 'bossmanMigrations');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/bossmanView'),
        ], 'bossman_views');

        $this->publishes([
            __DIR__ . '/resources' => base_path('app/Filament/Resources/'),
        ], 'bossman-filament-resources');

        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'bossman-migrations');


        $this->publishes([
            __DIR__.'/../Models' => base_path('app/Models/'),
        ], 'bossman-filament-models');

        $this->publishes([
            __DIR__.'/../public/plugins' => base_path('public/'),
        ], 'bossman-public-assets');


        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    }


}
