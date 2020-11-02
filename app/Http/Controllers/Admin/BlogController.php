<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogRequest;
use App\Models\Blog;
use App\Traits\AdminBaseController;

class BlogController extends Controller
{
    use AdminBaseController;

    protected $resourceName = 'blogs';
    protected $resourceModel = Blog::class;
    protected $resourceRequest = BlogRequest::class;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

}
