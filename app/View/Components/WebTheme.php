<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class WebTheme extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view(static::getThemeView());
    }

    public static function getThemes(): array
    {
        return [
            'tail-single' => 'themes.tail-single.app',
        ];
    }

    public static function getTheme(): string
    {
        return 'tail-single';
    }

    public static function getThemeView(): string
    {
        $themes = static::getThemes();

        return $themes[static::getTheme()];
    }
}
