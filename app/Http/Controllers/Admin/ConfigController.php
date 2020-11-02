<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Traits\AdminBaseController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * ConfigController
 * Author: trinhnv
 * Date: 2018/11/15 16:31
 */
class ConfigController extends Controller
{
    use AdminBaseController;

    /**
     * @var  string
     */
    protected $resourceName = 'config';

    /**
     * Fully qualified class name
     *
     * @var  string
     */
    protected $resourceModel = Config::class;

    /**
     * @var  string
     */
    protected $resourceTitle = 'Config';

    /**
     * Controller construct
     */
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $config = Config::firstOrNew();

        return view($this->getResourceIndexPath(), $this->filterSearchViewData($request, [
                'config' => $config,
                'addVarsForView' => [
                    '_pageSubtitle' => __('pages.setting')
                ],
            ] + $this->resourceData()));
    }

    public function saveConfig(ConfigRequest $request)
    {
        $data = $request->all();

        Config::updateOrCreate([
            'id' => BASE_CONFIG_ID
        ], $data);
        if ($request->has('image_file') && $request->image_file) {
            $imagePath = StorageHelper::saveLogoImage($request->file('image_file'));
        }

        Cache::forget(CACHE_BASE_CONFIG);
        flash()->success('Lưu thiết lập thành công');
        return redirect(route($this->resourceRoutesAlias . '.index'));
    }
}
