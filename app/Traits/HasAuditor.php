<?php

namespace App\Traits;

use App\Models\Auth\User;
use Doctrine\DBAL\Schema\Exception\ColumnDoesNotExist;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

trait HasAuditor
{
    public static function bootHasAuditor()
    {
        static::creating(function ($model) {
            if ( ! Schema::hasColumn($model->getTable(), 'created_by')) {
                throw new ColumnDoesNotExist('column created_by does not exist in current model table');
            }
            $model->created_by = auth()->user()->id;

        });
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
