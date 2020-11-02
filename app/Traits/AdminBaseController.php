<?php

namespace App\Traits;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

trait AdminBaseController
{
    use ResourceHelper;

    protected $msg_inserted = 'messages.inserted';
    protected $msg_not_inserted = 'messages.not_inserted';
    protected $msg_updated = 'messages.updated';
    protected $msg_not_updated = 'messages.not_updated';
    protected $msg_deleted = 'messages.deleted';
    protected $msg_not_deleted = 'messages.not_deleted';

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $paginatorData = [];
        $perPage = $this->getPageSize($request);
        if ($perPage != 15) {
            $paginatorData['per_page'] = $perPage;
        }
        $search = $request->input('search', '');
        if (!empty($search)) {
            $paginatorData['search'] = $search;
        }
        $records = $this->getSearchRecords($request, $perPage, $search, $paginatorData);

        return view($this->getResourceIndexPath(), $this->filterSearchViewData($request, [
                'records' => $records,
                'search' => $search,
                'perPage' => $perPage,
            ] + $this->resourceData()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', $this->getResourceModel());

        $class = $this->getResourceModel();
        return view($this->getResourceCreatePath(), $this->filterCreateViewData([
                'record' => new $class(),
            ] + $this->resourceData()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', $this->getResourceModel());

        $this->resourceValidate($request, 'store');

        $valuesToSave = $this->getValuesToSave($request);

        $request->merge($valuesToSave);

        $this->use_transaction && \DB::beginTransaction();
        $class = $this->getResourceModel();
        $model = new $class();
        $model->fill($this->alterValuesToSave($request, $valuesToSave));

        if ($record = $model->save()) {
            flash()->success(__($this->msg_inserted));

            return $this->getRedirectAfterSave($record, $request);
        } else {
            flash()->error(__($this->msg_not_inserted));
        }

        return $this->redirectBackTo(route($this->getResourceRoutesAlias() . '.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(route($this->getResourceRoutesAlias() . '.edit', $id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $record = $this->getResourceModel()::findOrFail($id);

        $this->authorize('update', $record);

        return view($this->getResourceEditPath(), $this->filterEditViewData($record, [
                'record' => $record,
            ] + $this->resourceData()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $record = $this->getResourceModel()::findOrFail($id);

        $this->authorize('update', $record);

        $this->resourceValidate($request, 'update', $record);

        $valuesToSave = $this->getValuesToSave($request, $record);

        $request->merge($valuesToSave);

        $this->use_transaction && \DB::beginTransaction();
        $record->fill($this->alterValuesToSave($request, $valuesToSave, $record));
        if ($record->save()) {
            flash()->success(__($this->msg_updated));

            return $this->getRedirectAfterSave($record, $request, true);
        } else {
            flash()->error(__($this->msg_not_updated));
        }

        return $this->redirectBackTo(route($this->getResourceRoutesAlias() . '.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy($id)
    {
        $record = $this->getResourceModel()::findOrFail($id);

        $this->authorize('delete', $record);

        if (!$this->checkDestroy($record)) {
            return redirect(route($this->getResourceRoutesAlias() . '.index'));
        }

        if ($record->delete()) {
            flash()->success(__($this->msg_deleted));
        } else {
            flash()->error(__($this->msg_not_deleted));
        }

        return $this->redirectBackTo(route($this->getResourceRoutesAlias() . '.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deletes(Request $request)
    {
        $this->authorize('deletes');
        $this->validate($request, [
            'ids' => 'required|array'
        ]);
        $ids = $request->get('ids');
        $records = $this->getResourceModel()::whereIn('id', $ids);
        $this->getAuthorizeModel($records);
        if ($records->delete()) {
            flash()->success(__($this->msg_deleted));
        } else {
            flash()->error(__($this->msg_not_deleted));
        }
        return $this->redirectBackTo(route($this->getResourceRoutesAlias() . '.index'));
    }

    public function resourceData()
    {
        $resourceName = $this->getResourceAlias();
        return [
            'resourceName' => $resourceName,
            'resourceTitle' => __(ADMIN_PREFIX . '.' . $resourceName),
            'resourceRoutesAlias' => ADMIN_PREFIX . '::' . $resourceName,
        ];
    }

    public function authorize($ability, $arguments = [])
    {

    }

    private function getResourceIndexPath()
    {
        return ADMIN_PREFIX . "." . $this->resourceName . ".index";
    }

    private function getResourceCreatePath()
    {
        return ADMIN_PREFIX . "." . $this->resourceName . ".create";
    }

    private function getResourceEditPath()
    {
        return ADMIN_PREFIX . "." . $this->resourceName . ".edit";
    }
}
