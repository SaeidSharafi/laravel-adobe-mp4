<?php

namespace App\Http\Controllers\Export;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Services\AlertResponseService;
use Exception;
use Illuminate\Support\Facades\App;
use Spatie\QueryBuilder\QueryBuilder;

class ExportUserController extends Controller
{
    public function __invoke()
    {
        $this->authorize('viewAny', User::class);

        try {
            $students = QueryBuilder::for(User::class)
                ->select(UserExport::getColumns())
                ->defaultSort('last_name', 'first_name')
                ->allowedSorts(['first_name', 'last_name', 'phone'])
                ->allowedFilters(['first_name', 'last_name', 'phone'])
                ->getQuery();

            return (new UserExport($students))->download('users.xlsx');
        } catch (Exception $exception) {
            if ( ! App::environment('local')) {
                report($exception);
                return redirect()->back()
                    ->withStatus(AlertResponseService::error(__('response.csv.error')));
            }

            throw $exception;
        }
    }
}
