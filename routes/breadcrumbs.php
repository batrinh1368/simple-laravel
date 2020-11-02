<?php
Breadcrumbs::for(ADMIN_PREFIX, function ($trail) {
    $trail->push(__(ADMIN_PREFIX . '.dashboard'), route(ADMIN_PREFIX . '::dashboard'));
});

$resources = [
    'config',
    'users',
    'blogs',
];
foreach ($resources as $resourceName) {
    $parent = 'admin';
    $title = __(ADMIN_PREFIX . '.' . $resourceName);
    $resource = 'admin::' . $resourceName;

    // List
    Breadcrumbs::register($resource, function ($breadcrumbs) use ($resource, $title, $parent) {
        $breadcrumbs->parent($parent);
        $breadcrumbs->push($title, route($resource . '.index'));
    });
    // Create
    Breadcrumbs::register($resource . '.create', function ($breadcrumbs) use ($resource, $title) {
        $breadcrumbs->parent($resource);
        $breadcrumbs->push(__('messages.create_data', ['resource' => $title]), route($resource . '.create'));
    });
    // Edit
    Breadcrumbs::register($resource . '.edit', function ($breadcrumbs, $id, $pageTitle = null) use ($resource, $title) {
        $breadcrumbs->parent($resource);
        $breadcrumbs->push($pageTitle ?? __('messages.edit_data', ['resource' => $title]), route($resource . '.edit', $id));
    });
}
