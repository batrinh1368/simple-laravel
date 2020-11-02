<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Traits\AdminBaseController;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use AdminBaseController;

    protected $resourceName = 'users';
    protected $resourceModel = User::class;
    protected $resourceRequest = UserRequest::class;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function alterValuesToSave(Request $request, $values, $record = null)
    {
        if (empty($values['password'])) {
            unset($values['password']);
        }

        return $values;
    }

}
