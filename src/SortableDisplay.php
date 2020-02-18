<?php

namespace Dcat\Admin\Extension\GridSortable;

use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Displayers\AbstractDisplayer;

class SortableDisplay extends AbstractDisplayer
{
    protected function script()
    {
        $id = $this->grid->tableId();

        $script = <<<JS
$("#{$id} tbody").sortable({
    placeholder: "sort-highlight",
    handle: ".grid-sortable-handle",
    forcePlaceholderSize: true,
    zIndex: 999999
}).on("sortupdate", function(event, ui) {

    var sorts = [];
    $(this).find('.grid-sortable-handle').each(function () {
        sorts.push($(this).data());
    });
    
    $('#{$id}').closest('.row').first().find('.grid-save-order-btn').data('sort', sorts).show();
});
JS;
        Admin::script($script);
    }

    protected function getRowSort($sortName)
    {
        return $this->row->{$sortName};
    }

    public function display($sortName = null)
    {
        $this->script();

        $key  = $this->key();
        $sort = $this->getRowSort($sortName);

        return <<<HTML
<a class="grid-sortable-handle" style="cursor:move;" data-key="{$key}" data-sort="{$sort}">
   <i class="fa fa-ellipsis-v"></i>
   <i class="fa fa-ellipsis-v"></i>
</a>
HTML;
    }
}
