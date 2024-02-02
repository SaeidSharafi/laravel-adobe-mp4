<?php

namespace App\Providers;

use App\DataTable\InertiaTableExtended;
use Illuminate\Support\ServiceProvider;
use Inertia\Response as InertiaResponse;

class InertiaTableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        InertiaResponse::macro('getQueryBuilderProps', fn () => $this->props['queryBuilderProps'] ?? []);

        InertiaResponse::macro('exTable', function (?callable $withTableBuilder = null) {
            $tableBuilder = new InertiaTableExtended(request());

            if ($withTableBuilder) {
                $withTableBuilder($tableBuilder);
            }

            return $tableBuilder->applyTo($this);
        });
    }
}
