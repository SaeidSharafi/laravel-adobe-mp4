<?php

namespace App\Http\Controllers\Reports;

use App\DataTable\FilterTypeEnum;
use App\DataTable\InertiaTableExtended;
use App\Enums\ActivityLogSubjectEnum;
use App\Http\Controllers\Controller;
use App\Services\AlertResponseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use ValueError;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the Activities.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Activity::class);

        $logs = QueryBuilder::for(Activity::class)
            ->allowedSorts([
                AllowedSort::field('created_at_s', 'created_at'),
            ])
            ->allowedFilters([
                'causer_id',
                AllowedFilter::exact('subject', 'subject_type'),
                AllowedFilter::callback('from_date', function ($query, $value) {
                    $query->where('created_at', '>=', $value);
                }),
                AllowedFilter::callback('to_date', function ($query, $value) {
                    $query->where('created_at', '<=', $value);
                }),
                AllowedFilter::callback('name', function ($query, $value) {
                    $query->whereRaw("JSON_EXTRACT(`properties`, '$.attributes.code') LIKE ?", ["%" . $value . "%"]);
                    $query->orWhereRaw("JSON_EXTRACT(`properties`, '$.attributes.name') LIKE ?", ["%" . $value . "%"]);
                    $query->orWhereRaw("JSON_EXTRACT(`properties`, '$.attributes.title') LIKE ?", ["%" . $value . "%"]);
                }),
            ])
            ->defaultSort('-created_at')
            ->with('causer', 'subject')
            ->paginate(request('perPage', 15))
            ->through(function ($log) {
                $log->created_at_s  = $log->created_at->verta()->format('Y-m-d');
                $log->subject_label = ActivityLogSubjectEnum::from($log->subject_type)->translate();
                return $log;
            })
            ->withQueryString();

        return Inertia::render('Reports/Log/Index', compact('logs'))
            ->exTable(function (InertiaTableExtended $table) {
                $table->selectFilter(
                    key: 'causer_id',
                    options: [],
                    label: __('auth.user.title.self'),
                    route: 'list.user',
                    type: FilterTypeEnum::SEARCHABLE,
                );
                $table->selectFilter(
                    key: 'subject',
                    options: ActivityLogSubjectEnum::getkeyValuePair(),
                    label: __('reports.log.subject'),
                );
                $table->selectFilter(
                    key: 'from_date',
                    options: [],
                    label: __('general.from_date'),
                    type: FilterTypeEnum::DATE,
                    noFilterOptionLabel: 'All'
                );
                $table->selectFilter(
                    key: 'to_date',
                    options: [],
                    label: __('general.to_date'),
                    type: FilterTypeEnum::DATE,
                    noFilterOptionLabel: 'All'
                );
                $table->searchInput('name', __('عنوان'));
                $table
                    ->column('description', translation: __('reports.log.description'), sortable: true)
                    ->column('subject_label', translation: __('reports.log.subject'), sortable: true)
                    ->column(label: 'causer', translation: __('reports.log.causer'), sortable: true)
                    ->column('created_at_s', translation: __('reports.log.created_at'), sortable: true)
                    ->column(label: 'actions', translation: __('general.action'));
            });
    }

    /**
     * Display the specified Acitivty.
     *
     * @param  int  $id
     *
     * @return Response|RedirectResponse
     */
    public function view(int $id): Response|RedirectResponse
    {
        $activity = Activity::with('causer')->findOrFail($id);
        $this->authorize('view', $activity);
        try {
            $type = ActivityLogSubjectEnum::from($activity->subject_type);

            return Inertia::render('Reports/Log/View', compact('activity'));
        } catch (ValueError $exception) {
            return redirect()->back()
                ->withStatus(AlertResponseService::error('Invalid Subject Type'));
        }
    }
}
