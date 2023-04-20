<?php

namespace BossmanFilamentApp\Pages\CustomActions;

use Filament\Facades\SpatieLaravelTranslatablePlugin;
use Filament\Pages\Actions\SelectAction;

class CustomLangSwitcher extends SelectAction
{


    public static function getDefaultName(): ?string
    {
        return 'activeLocale';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Lang Switcher');
        $this->options(function (): array {
            $livewire = $this->getLivewire();
            if (!method_exists($livewire, 'getTranslatableLocales')) {
                return [];
            }
            $locales = [];
            foreach ($livewire->getTranslatableLocales() as $locale) {
                $locales[$locale] = SpatieLaravelTranslatablePlugin::getLocaleLabel($locale) ?? $locale;
            }
            return $locales;
        });

    }

}
