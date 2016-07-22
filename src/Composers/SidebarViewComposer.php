<?php

namespace TypiCMS\Modules\Products\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;

class SidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(trans('global.menus.shop'), function (SidebarGroup $group) {
            $group->addItem(trans('products::global.name'), function (SidebarItem $item) {
                $item->icon = config('typicms.products.sidebar.icon');
                $item->weight = config('typicms.products.sidebar.weight');
                $item->route('admin::index-products');
                $item->append('admin::create-product');
                $item->authorize(
                    Gate::allows('index-products')
                );
            });
        });
    }
}
