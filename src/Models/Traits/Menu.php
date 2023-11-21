<?php

namespace Sdkconsultoria\Core\Models\Traits;

use Base;

trait Menu
{
    public static function makeMenu(string $icon, array $extra_urls = [], $default_search = '')
    {
        $called_class = get_called_class();
        $model = new $called_class;
        $end_point = $model->getApiEndpoint();
        return [
            'name' => $model->getTranslations()['plural'],
            'icon' => Base::icon($icon, ['class' => 'h-6 w-6']),
            'url' =>  $end_point.'.index',
            'crud' => $end_point,
            'extra_urls' => $extra_urls,
            'default_search' => $default_search,
        ];
    }
}
