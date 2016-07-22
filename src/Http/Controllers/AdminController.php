<?php

namespace TypiCMS\Modules\Products\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BaseAdminController;
use TypiCMS\Modules\Products\Http\Requests\FormRequest;
use TypiCMS\Modules\Products\Models\Product;
use TypiCMS\Modules\Products\Repositories\ProductInterface;

class AdminController extends BaseAdminController
{
    public function __construct(ProductInterface $product)
    {
        parent::__construct($product);
    }

    /**
     * List models.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $models = $this->repository->all([], true);
        app('JavaScript')->put('models', $models);

        return view('products::admin.index');
    }

    /**
     * Create form for a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = $this->repository->getModel();

        return view('products::admin.create')
            ->with(compact('model'));
    }

    /**
     * Edit form for the specified resource.
     *
     * @param \TypiCMS\Modules\Products\Models\Product $product
     *
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        return view('products::admin.edit')
            ->with(['model' => $product]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \TypiCMS\Modules\Products\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(FormRequest $request)
    {
        $product = $this->repository->create($request->all());

        return $this->redirect($request, $product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \TypiCMS\Modules\Products\Models\Product            $product
     * @param \TypiCMS\Modules\Products\Http\Requests\FormRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Product $product, FormRequest $request)
    {
        $this->repository->update($request->all());

        return $this->redirect($request, $product);
    }
}
