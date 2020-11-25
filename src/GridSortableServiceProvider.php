<?php

namespace Dcat\Admin\Extension\GridSortable;

use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Grid;

class GridSortableServiceProvider extends ServiceProvider
{
    protected $js = [
        'js/sortable.min.js',
    ];
    protected $column = '__sortable__';

    public function register()
    {
        //
    }

    public function init()
    {
        parent::init();

        $column = $this->column;

        Grid::macro('sortable', function ($sortName = null) use ($column) {
            if ($sortName === null) {
                $sortName = $this->model()->repository()->model()->determineOrderColumnName();
            }

            /* @var $this Grid */
            $this->tools(new SaveOrderButton($sortName));

            if (!request()->has($sortName)) {
                $this->model()->ordered();
            }

            $this->column($column, ' ')
                ->displayUsing(SortableDisplay::class, [$sortName]);
        });
    }
}
