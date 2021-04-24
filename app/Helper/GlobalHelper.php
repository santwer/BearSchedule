<?php

use KgBot\LaravelLocalization\Facades\ExportLocalizations;

function locale_route($name, $parameters = [], $absolute = true)
{
    // team() returns a team object
    $parameters = array_merge(['locale' => user_locale()], $parameters);
    return route($name, $parameters, $absolute);
}

function user_locale(): string
{
    $request = \request();


    if (in_array($request->segment(1), config('app.locales'))) {
        return $request->segment(1);
    }

    if (($locale = session('locale')) !== null) {
        return $locale;
    }
    foreach ($request->getLanguages() as $language) {
        if (in_array($language, config('app.locales'))) {
            return $language;
        }
    }

    return config('app.fallback_locale');
}

function get_langs() {
    $locals = [];
    foreach(config('app.locales') as $local) {
        $locals[$local] = __('general.language_own', [], $local);
    }
    return collect($locals);
}

function localeDateFormat($time = false)
{
    return __('general.dateformat').($time ? ' H:i' : '');
}

function getTranslatationMessages()
{
    return ExportLocalizations::export()->toFlat();
}
