<?php

namespace App\DataTable;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Inertia\Response;

class InertiaTableExtended
{
    use Conditionable;

    private string $name          = 'default';
    private string $pageName      = 'page';
    private array $perPageOptions = [15, 30, 50, 100];
    private Request $request;
    private Collection $columns;
    private Collection $searchInputs;
    private Collection $filters;
    private string $defaultSort = '';

    private static bool|string $defaultGlobalSearch = false;
    private static array $defaultQueryBuilderConfig = [];

    public function __construct(Request $request)
    {
        $this->request      = $request;
        $this->columns      = new Collection();
        $this->searchInputs = new Collection();
        $this->filters      = new Collection();

        if (false !== static::$defaultGlobalSearch) {
            $this->withGlobalSearch(static::$defaultGlobalSearch);
        }
    }

    /**
     * Set a default for global search.
     *
     * @param  bool|string  $label
     *
     * @return void
     */
    public static function defaultGlobalSearch(bool|string $label = 'Search...')
    {
        static::$defaultGlobalSearch = false !== $label ? __($label) : false;
    }

    /**
     * Helper method to update the Spatie Query Builder parameter config.
     *
     * @param  string  $name
     *
     * @return void
     */
    public static function updateQueryBuilderParameters(string $name)
    {
        if (empty(static::$defaultQueryBuilderConfig)) {
            static::$defaultQueryBuilderConfig = config('query-builder.parameters');
        }

        $newConfig = collect(static::$defaultQueryBuilderConfig)->map(fn ($value) => "{$name}_{$value}")->all();

        config(['query-builder.parameters' => $newConfig]);
    }

    /**
     * Name for this table.
     *
     * @param  string  $name
     *
     * @return self
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Page name for this table.
     *
     * @param  string  $pageName
     *
     * @return self
     */
    public function pageName(string $pageName): self
    {
        $this->pageName = $pageName;

        return $this;
    }

    /**
     * Per Page options for this table.
     *
     * @param  array  $perPageOptions
     *
     * @return self
     */
    public function perPageOptions(array $perPageOptions): self
    {
        $this->perPageOptions = $perPageOptions;

        return $this;
    }

    /**
     * Default sort for this table.
     *
     * @param  string  $defaultSort
     *
     * @return self
     */
    public function defaultSort(string $defaultSort): self
    {
        $this->defaultSort = $defaultSort;

        return $this;
    }

    /**
     * Add a column to the query builder.
     *
     * @param  string|null  $key
     * @param  string|null  $label
     * @param  string|null  $translation
     * @param  bool  $canBeHidden
     * @param  bool  $hidden
     * @param  bool  $sortable
     * @param  bool  $searchable
     *
     * @return self
     */
    public function column(
        ?string $key = null,
        ?string $label = null,
        ?string $translation = null,
        bool $canBeHidden = true,
        bool $hidden = false,
        bool $sortable = false,
        bool $selectable = false,
        bool $searchable = false,
        bool $isUrl = false,
    ): self {
        $key         = $key ?: Str::kebab($label);
        $label       = $label ?: Str::headline($key);
        $translation = $translation ?: $label;

        $this->columns = $this->columns->reject(fn (Column $column) => $column->key === $key)->push($column = new Column(
            key: $key,
            label: $label,
            translation: $translation,
            canBeHidden: $canBeHidden,
            hidden: $hidden,
            sortable: $sortable,
            selectable: $selectable,
            isUrl: $isUrl,
            sorted: false
        ))->values();

        if ($searchable) {
            $this->searchInput($column->key, $column->label);
        }

        return $this;
    }

    /**
     * Helper method to add a global search input.
     *
     * @param  string|null  $label
     *
     * @return self
     */
    public function withGlobalSearch(?string $label = null): self
    {
        return $this->searchInput('global', $label ?: __('Search...'));
    }

    /**
     * Add a search input to query builder.
     *
     * @param  string  $key
     * @param  string|null  $label
     * @param  string|null  $defaultValue
     *
     * @return self
     */
    public function searchInput(string $key, ?string $label = null, ?string $defaultValue = null): self
    {
        $this->searchInputs = $this->searchInputs->reject(fn (SearchInput $searchInput) => $searchInput->key === $key)
            ->push(new SearchInput(
                key: $key,
                label: $label ?: Str::headline($key),
                value: $defaultValue
            ))->values();

        return $this;
    }

    /**
     * Add a select filter to the query builder.
     *
     * @param  string  $key
     * @param  array  $options
     * @param  string|null  $label
     * @param  string|null  $defaultValue
     * @param  bool  $noFilterOption
     * @param  string|null  $noFilterOptionLabel
     *
     * @return self
     */
    public function selectFilter(
        string $key,
        array $options,
        ?string $label = null,
        ?string $route = null,
        ?string $defaultValue = null,
        FilterTypeEnum $type = FilterTypeEnum::SELECT,
        bool $noFilterOption = true,
        bool $noFilterOptionKeyValue = false,
        bool $canClear = true,
        ?string $noFilterOptionLabel = null,
        null|array $dependsOn = null
    ): self {
        $this->filters = $this->filters->reject(fn (Filter $filter) => $filter->key === $key)->push(new Filter(
            key: $key,
            label: $label ?: Str::headline($key),
            options: $options,
            noFilterOption: $noFilterOption,
            noFilterOptionKeyValue: $noFilterOptionKeyValue,
            noFilterOptionLabel: $noFilterOptionLabel ?: __('general.all'),
            canClear: $canClear,
            type: $type->value,
            dependsOn: $dependsOn,
            value: $defaultValue,
            route: $route
        ))->values();

        return $this;
    }

    /**
     * Give the query builder props to the given Inertia response.
     *
     * @param  Response  $response
     *
     * @return Response
     */
    public function applyTo(Response $response): Response
    {
        $props = array_merge($response->getQueryBuilderProps(), [
            $this->name => $this->getQueryBuilderProps(),
        ]);

        return $response->with('queryBuilderProps', $props);
    }

    /**
     * Collects all properties and sets the default
     * values from the request query.
     *
     * @return array
     */
    protected function getQueryBuilderProps(): array
    {
        return [
            'defaultVisibleToggleableColumns' => $this->columns->reject->hidden->map->key->sort()->values(),
            'columns'                         => $this->transformColumns(),
            'hasHiddenColumns'                => $this->columns->filter->hidden->isNotEmpty(),
            'hasToggleableColumns'            => $this->columns->filter->canBeHidden->isNotEmpty(),

            'filters'           => $this->transformFilters(),
            'hasFilters'        => $this->filters->isNotEmpty(),
            'hasEnabledFilters' => $this->filters->whereNotNull('value')->isNotEmpty(),

            'searchInputs'                => $searchInputs              = $this->transformSearchInputs(),
            'searchInputsWithoutGlobal'   => $searchInputsWithoutGlobal = $searchInputs->where('key', '!=', 'global'),
            'hasSearchInputs'             => $searchInputsWithoutGlobal->isNotEmpty(),
            'hasSearchInputsWithValue'    => $searchInputsWithoutGlobal->whereNotNull('value')->isNotEmpty(),
            'hasSearchInputsWithoutValue' => $searchInputsWithoutGlobal->whereNull('value')->isNotEmpty(),

            'globalSearch' => $this->searchInputs->firstWhere('key', 'global'),

            'cursor'         => $this->query('cursor'),
            'sort'           => $this->query('sort', $this->defaultSort) ?: null,
            'defaultSort'    => $this->defaultSort,
            'page'           => Paginator::resolveCurrentPage($this->pageName),
            'pageName'       => $this->pageName,
            'perPageOptions' => $this->perPageOptions,
        ];
    }

    /**
     * Transform the columns collection so it can be used in the Inertia front-end.
     *
     * @return Collection
     */
    protected function transformColumns(): Collection
    {
        $columns = $this->query('columns', []);

        $sort = $this->query('sort', $this->defaultSort);

        return $this->columns->map(function (Column $column) use ($columns, $sort) {
            $key = $column->key;

            if ( ! empty($columns)) {
                $column->hidden = ! in_array($key, $columns);
            }

            if ($sort === $key) {
                $column->sorted = 'asc';
            } elseif ($sort === "-{$key}") {
                $column->sorted = 'desc';
            }

            return $column;
        });
    }

    /**
     * Transform the search collection so it can be used in the Inertia front-end.
     *
     * @return Collection
     */
    protected function transformFilters(): Collection
    {
        $filters = $this->filters;

        $queryFilters = $this->query('filter', []);

        if (empty($queryFilters)) {
            return $filters;
        }

        return $filters->map(function (Filter $filter) use ($queryFilters) {
            if (array_key_exists($filter->key, $queryFilters)) {
                $filter->value = $queryFilters[$filter->key];
            }

            return $filter;
        });
    }

    /**
     * Transform the filters collection so it can be used in the Inertia front-end.
     *
     * @return Collection
     */
    protected function transformSearchInputs(): Collection
    {
        $filters = $this->query('filter', []);

        if (empty($filters)) {
            return $this->searchInputs;
        }

        return $this->searchInputs->map(function (SearchInput $searchInput) use ($filters) {
            if (array_key_exists($searchInput->key, $filters)) {
                $searchInput->value = $filters[$searchInput->key];
            }

            return $searchInput;
        });
    }

    /**
     * Retrieve a query string item from the request.
     *
     * @param  string  $key
     * @param  mixed|null  $default
     *
     * @return mixed
     */
    private function query(string $key, $default = null)
    {
        return $this->request->query(
            'default' === $this->name ? $key : "{$this->name}_{$key}",
            $default
        );
    }
}
