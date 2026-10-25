<?php

namespace Botble\Membership\Tables;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Membership\Repositories\Interfaces\PlanInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class PlanTable extends TableAbstract
{
    protected $hasActions = true;
    protected $hasFilter = true;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, PlanInterface $planRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $planRepository;

        if (!Auth::user()->hasAnyPermission(['plans.edit', 'plans.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('plans.edit')) {
                    return clean($item->name);
                }

                $badge = $item->badge_color
                    ? '<span class="badge" style="background-color: ' . e($item->badge_color) . '; color: #fff; margin-right: 5px;">' . e($item->name) . '</span>'
                    : '';

                return $badge . Html::link(route('plans.edit', $item->id), clean($item->name));
            })
            ->editColumn('amount', function ($item) {
                return format_price($item->amount);
            })
            ->editColumn('duration', function ($item) {
                $val = $item->duration_value ?: 1;
                $unit = ucfirst($item->duration_unit ?: 'Year');
                return "{$val} {$unit}";
            })
            ->editColumn('shipping_discount_percentage', function ($item) {
                return ((float) $item->shipping_discount_percentage) . '%';
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return $this->getOperations('plans.edit', 'plans.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function query()
    {
        $query = $this->repository->getModel()->select([
            'id',
            'name',
            'amount',
            'duration_value',
            'duration_unit',
            'shipping_discount_percentage',
            'badge_color',
            'status',
            'created_at',
        ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $this->repository->getModel()));
    }

    public function columns()
    {
        return [
            'id' => [
                'name'  => 'id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'name'  => 'name',
                'title' => trans('plugins/membership::plans.plan_name'),
                'class' => 'text-start',
            ],
            'amount' => [
                'name'  => 'amount',
                'title' => trans('plugins/membership::plans.amount'),
                'class' => 'text-start',
            ],
            'duration' => [
                'name'  => 'duration_value',
                'title' => trans('plugins/membership::plans.duration_days'),
                'class' => 'text-center',
            ],
            'shipping_discount_percentage' => [
                'name'  => 'shipping_discount_percentage',
                'title' => trans('plugins/membership::plans.shipping_discount'),
                'class' => 'text-center',
            ],
            'status' => [
                'name'  => 'status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
            'created_at' => [
                'name'  => 'created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
        ];
    }

    public function buttons()
    {
        return $this->addCreateButton(route('plans.create'), 'plans.create');
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('plans.deletes'), 'plans.destroy', parent::bulkActions());
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
        ];
    }
}
