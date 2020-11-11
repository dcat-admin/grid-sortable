<?php

namespace Dcat\Admin\Extension\GridSortable;

use Dcat\Admin\Admin;
use Dcat\Admin\Grid\Tools\AbstractTool;

class SaveOrderButton extends AbstractTool
{
    protected $sortColumn;

    public function __construct($column)
    {
        $this->sortColumn = $column;
    }

    protected function script()
    {
        $route = admin_base_path('extension/grid-sort');
        $repository = $this->parent->model()->repository();
        if (method_exists($repository, 'getOriginalClassName')) {
            $class = $repository->getOriginalClassName();
        } else {
            $class = get_class($repository);
        }
        $class = str_replace('\\', '\\\\', $class);

        $script = <<<JS
$('.grid-save-order-btn').click(function () {
    $.post('{$route}', {
        _token: Dcat.token,
        _model: '{$class}',
        _sort: $(this).data('sort'),
        _column: '{$this->sortColumn}',
    },
    function(data){
    
        if (data.status) {
            Dcat.success(data.message);
            Dcat.reload();
        } else {
            Dcat.error(data.message);
        }
    });
});
    
JS;
        Admin::script($script);
    }

    public function render()
    {
        $this->script();

        $text = admin_trans_label('Save order');

        return <<<HTML
<button type="button" class="btn btn-primary grid-refresh grid-save-order-btn" style="margin-left:8px;display:none;">
    <i class="fa fa-save"></i><span class="hidden-xs">&nbsp;&nbsp;{$text}</span>
</button>
HTML;
    }
}

