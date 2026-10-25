<?php

namespace Botble\Membership\Tables;

use BaseHelper;
use Botble\Membership\Repositories\Interfaces\MembershipInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MembershipTable extends TableAbstract
{
    protected $hasActions = true;
    protected $hasFilter = true;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, MembershipInterface $membershipRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $membershipRepository;

        if (!Auth::user()->hasAnyPermission(['memberships.edit', 'memberships.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('user_name', function ($item) {
                $name = $item->user->name ?: ($item->cus_name ?: 'N/A');
                if (!Auth::user()->hasPermission('memberships.edit')) {
                    return clean($name);
                }

                return Html::link(route('memberships.edit', $item->id), clean($name));
            })
            ->editColumn('plan_name', function ($item) {
                if (!$item->plan || !$item->plan->name) {
                    return 'N/A';
                }

                $badge = $item->plan->badge_color
                    ? '<span class="badge" style="background-color: ' . e($item->plan->badge_color) . '; color: #fff;">' . e($item->plan->name) . '</span>'
                    : '<span class="badge bg-secondary">' . e($item->plan->name) . '</span>';

                return $badge;
            })
            ->editColumn('start_date', function ($item) {
                return $item->start_date ? BaseHelper::formatDate($item->start_date) : '';
            })
            ->editColumn('end_date', function ($item) {
                return $item->end_date ? BaseHelper::formatDate($item->end_date) : '';
            })
            ->editColumn('savings', function ($item) {
                return '<span class="text-success font-weight-bold">' . format_price($item->savings ?: 0) . '</span>';
            })
            ->editColumn('status', function ($item) {
                $class = 'secondary';
                if ($item->status === 'active') {
                    $class = 'success';
                } elseif ($item->status === 'pending_payment') {
                    $class = 'warning';
                }
                return '<span class="label label-' . $class . ' status-label">' . ucfirst($item->status) . '</span>';
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return $this->getOperations('memberships.edit', 'memberships.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function query()
    {
        $query = $this->repository->getModel()
            ->with(['user', 'plan'])
            ->select([
                'memberships.id',
                'memberships.user_id',
                'memberships.plan_id',
                'memberships.start_date',
                'memberships.end_date',
                'memberships.savings',
                'memberships.status',
                'memberships.created_at',
            ]);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $this->repository->getModel()));
    }

    public function columns()
    {
        return [
            'id' => [
                'name'  => 'memberships.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'user_name' => [
                'name'  => 'memberships.user_id',
                'title' => trans('plugins/membership::membership.user'),
                'class' => 'text-start',
            ],
            'plan_name' => [
                'name'  => 'memberships.plan_id',
                'title' => trans('plugins/membership::membership.plan'),
                'class' => 'text-start',
            ],
            'start_date' => [
                'name'  => 'memberships.start_date',
                'title' => trans('plugins/membership::membership.start_date'),
                'class' => 'text-center',
            ],
            'end_date' => [
                'name'  => 'memberships.end_date',
                'title' => trans('plugins/membership::membership.end_date'),
                'class' => 'text-center',
            ],
            'savings' => [
                'name'  => 'memberships.savings',
                'title' => trans('plugins/membership::membership.savings'),
                'class' => 'text-center',
            ],
            'status' => [
                'name'  => 'memberships.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                'class' => 'text-center',
            ],
        ];
    }

    public function buttons()
    {
        return $this->addCreateButton(route('memberships.create'), 'memberships.create');
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('memberships.deletes'), 'memberships.destroy', parent::bulkActions());
    }

    public function getBulkChanges(): array
    {
        return [
            'status' => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => [
                    'active'          => trans('plugins/membership::membership.active'),
                    'inactive'        => trans('plugins/membership::membership.inactive'),
                    'pending_payment' => trans('plugins/membership::membership.pending_payment'),
                ],
                'validate' => 'required|in:active,inactive,pending_payment',
            ],
        ];
    }
}
