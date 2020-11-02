<?php

if (!function_exists('__admin_sortable')) {
    /**
     * Echo sortable url
     *
     * @param $field
     *
     * @return string
     */
    function __admin_sortable($field)
    {
        echo '<a href="' . \Request::fullUrlWithQuery(['sort' => $field, 'is_desc' => !request('is_desc', false)]) . '"class="ic-ca">';
        if (request('sort') == $field && request('is_desc') == 1) {
            echo '<span class="dropup"><span class="caret"></span></span>';
        } else {
            echo '<span class="caret"></span>';
        }
        echo '</a>';
    }
}

if (!function_exists('__a')) {
    function __a($key, $attrs = [])
    {
        return __('admin.' . $key, $attrs);
    }
}
