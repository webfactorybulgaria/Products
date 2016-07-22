<?php

namespace TypiCMS\Modules\Products\Http\Controllers;

use TypiCMS\Modules\Core\Http\Controllers\BasePublicController;
use TypiCMS\Modules\Products\Repositories\ProductInterface;

class PublicController extends BasePublicController
{
    public function __construct(ProductInterface $product)
    {
        parent::__construct($product);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $models = $this->repository->all();

        return view('products::public.index')
            ->with(compact('models'));
    }

    /**
     * Show news.
     *
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $model = $this->repository->bySlug($slug);

        return view('products::public.show')
            ->with(compact('model'));
    }
}
