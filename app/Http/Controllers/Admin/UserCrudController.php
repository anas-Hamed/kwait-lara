<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Constants;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\ToggleActiveOperation;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;
use Prologue\Alerts\Facades\Alert;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use ListOperation;
    use ShowOperation;
    use UpdateOperation {
        update as _update;
    }
    use ToggleActiveOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings(__('crud.user'), __('crud.users'));
        $this->crud->addClause('where', 'is_admin', false);
    }

    protected function setupListOperation()
    {
        $this->crud->enablePersistentTable();
        $this->crud->setOperationSetting('responsiveTable', true);
        $this->crud->query->withCount('companies');

        // Avatar + Name as a combined closure column
        CRUD::addColumn([
            'name' => 'name',
            'label' => __('crud.name'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                $initials = mb_strtoupper(mb_substr($entry->name, 0, 1));
                $colors = ['#0891b2', '#6366f1', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];
                $color = $colors[$entry->id % count($colors)];
                return '<div class="d-flex align-items-center gap-2">
                    <div class="user-avatar" style="background:' . $color . '">' . e($initials) . '</div>
                    <div>
                        <div class="fw-semibold">' . e($entry->name) . '</div>
                        <div class="text-muted small">' . e($entry->email) . '</div>
                    </div>
                </div>';
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $term = '%' . $searchTerm . '%';
                $query->orWhere('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term)
                    ->orWhereHas('companies', function ($q) use ($term) {
                        $q->where('ar_name', 'like', $term)
                            ->orWhere('en_name', 'like', $term)
                            ->orWhere('phone', 'like', $term);
                    });
            },
        ]);

        CRUD::addColumn([
            'name' => 'phone',
            'label' => __('crud.phone'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if (!$entry->phone) {
                    return '<span class="text-muted">—</span>';
                }
                return '<span dir="ltr">' . e($entry->phone) . '</span>';
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('phone', 'like', '%' . $searchTerm . '%');
            },
        ]);

        CRUD::addColumn([
            'name' => 'companies_count',
            'label' => __('crud.companies_count'),
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'is_active',
            'label' => __('crud.status'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if ($entry->is_active) {
                    return '<span class="status-badge status-active">' . __('crud.active') . '</span>';
                }
                return '<span class="status-badge status-inactive">' . __('crud.inactive') . '</span>';
            },
        ]);

        CRUD::addColumn([
            'name' => 'created_at',
            'label' => __('crud.joined'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if (!$entry->created_at) {
                    return '<span class="text-muted">—</span>';
                }
                return '<div class="text-muted small">' . $entry->created_at->translatedFormat('d M Y') . '</div>';
            },
        ]);

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'active',
            'label' => __('crud.inactive_only'),
        ],
            false,
            function () {
                $this->crud->addClause('where', 'is_active', false);
            });

        $this->crud->addFilter([
            'type' => 'simple',
            'name' => 'has_companies',
            'label' => __('crud.has_companies'),
        ],
            false,
            function () {
                $this->crud->addClause('has', 'companies');
            });

        $this->crud->addFilter([
            'name' => 'created_range',
            'type' => 'date_range',
            'label' => __('crud.joined'),
        ],
            false,
            function ($value) {
                $dates = json_decode($value);
                if (!empty($dates->from)) {
                    $this->crud->addClause('where', 'created_at', '>=', $dates->from);
                }
                if (!empty($dates->to)) {
                    $this->crud->addClause('where', 'created_at', '<=', $dates->to . ' 23:59:59');
                }
            });

        $this->crud->setDefaultPageLength(15);
        $this->crud->orderBy('created_at', 'desc');
    }

    public function setupShowOperation()
    {
        $this->crud->setShowContentClass('col-md-12');
        $this->crud->set('show.setFromDb', false);

        CRUD::column('name')->label(__('crud.name'));
        CRUD::column('email')->label(__('crud.email'));
        CRUD::column('phone')->label(__('crud.phone'))->wrapper(['dir' => 'ltr']);

        CRUD::addColumn([
            'name' => 'is_active',
            'label' => __('crud.status'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                if ($entry->is_active) {
                    return '<span class="status-badge status-active">' . __('crud.active') . '</span>';
                }
                return '<span class="status-badge status-inactive">' . __('crud.inactive') . '</span>';
            },
        ]);

        CRUD::column('created_at')->label(__('crud.joined'));

        CRUD::addColumn([
            'name' => 'companies_total',
            'label' => __('crud.companies_count'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                return '<strong>' . (int)$entry->companies()->count() . '</strong>';
            },
        ]);

        CRUD::addColumn([
            'name' => 'active_subscriptions',
            'label' => __('crud.subscribed_only'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                $count = \App\Models\Subscription::query()
                    ->whereIn('company_id', $entry->companies()->pluck('id'))
                    ->where('is_active', true)
                    ->count();
                if ($count > 0) {
                    return '<span class="status-badge status-active">' . $count . '</span>';
                }
                return '<span class="status-badge status-inactive">0</span>';
            },
        ]);

        CRUD::addColumn([
            'name' => 'last_activity',
            'label' => __('crud.last_activity'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                $candidates = [];
                $tokenLast = $entry->tokens()->max('last_used_at');
                if ($tokenLast) {
                    $candidates[] = \Illuminate\Support\Carbon::parse($tokenLast);
                }
                $latestCompany = $entry->companies()->max('created_at');
                if ($latestCompany) {
                    $candidates[] = \Illuminate\Support\Carbon::parse($latestCompany);
                }
                if ($entry->updated_at) {
                    $candidates[] = $entry->updated_at;
                }
                if (empty($candidates)) {
                    return '<span class="text-muted">—</span>';
                }
                $latest = collect($candidates)->max();
                return '<div>' . e($latest->translatedFormat('d M Y H:i')) . '</div>'
                    . '<div class="text-muted small">' . e($latest->diffForHumans()) . '</div>';
            },
        ]);

        CRUD::addColumn([
            'name' => 'companies_table',
            'label' => __('crud.companies'),
            'type' => 'custom_html',
            'value' => function ($entry) {
                $companies = $entry->companies()->with('category:id,name')->orderByDesc('created_at')->get();
                if ($companies->isEmpty()) {
                    return '<div class="text-muted">' . __('crud.no_companies') . '</div>';
                }
                $rows = '';
                foreach ($companies as $c) {
                    $statusActive = $c->is_active
                        ? '<span class="status-badge status-active">' . __('crud.active') . '</span>'
                        : '<span class="status-badge status-inactive">' . __('crud.inactive') . '</span>';
                    $statusPaid = $c->has_paid
                        ? '<span class="status-badge status-active">' . __('crud.paid') . '</span>'
                        : '<span class="status-badge status-inactive">' . __('crud.unpaid') . '</span>';
                    $url = backpack_url('company/' . $c->id . '/show');
                    $rows .= '<tr>'
                        . '<td><a href="' . e($url) . '">' . e($c->ar_name) . '</a><div class="text-muted small">' . e($c->en_name) . '</div></td>'
                        . '<td>' . e(optional($c->category)->name ?? '—') . '</td>'
                        . '<td>' . $statusActive . '</td>'
                        . '<td>' . $statusPaid . '</td>'
                        . '<td>' . ($c->created_at ? e($c->created_at->translatedFormat('d M Y')) : '—') . '</td>'
                        . '</tr>';
                }
                return '<div class="table-responsive"><table class="table table-sm table-bordered mb-0">'
                    . '<thead><tr>'
                    . '<th>' . __('crud.name') . '</th>'
                    . '<th>' . __('crud.category') . '</th>'
                    . '<th>' . __('crud.status') . '</th>'
                    . '<th>' . __('crud.is_paid') . '</th>'
                    . '<th>' . __('crud.created_at') . '</th>'
                    . '</tr></thead>'
                    . '<tbody>' . $rows . '</tbody></table></div>';
            },
        ]);
    }

    public function setupUpdateOperation()
    {
        $this->crud->setValidation(UserRequest::class);
        $this->crud->addField([
            'name' => 'password',
            'label' => __('crud.password'),
            'type' => 'password',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);
        $this->crud->addField([
            'name' => 'password_confirmation',
            'label' => __('crud.password_confirmation'),
            'type' => 'password',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);
    }

    public function update()
    {
        $this->crud->validateRequest();
        $this->handlePasswordInput($this->crud->getRequest());
        $this->crud->unsetValidation();
        return $this->_update();
    }

    protected function handlePasswordInput($request)
    {
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        if ($request->input('password')) {
            $request->request->add(['plaintext_password' => $request->input('password')]);
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }
        return $request;
    }
}
