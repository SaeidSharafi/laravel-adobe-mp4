<?php

namespace App\Traits;

use App\Exceptions\RelationshipException;

trait SecureDelete
{
    /**
     * Soft delete only when there is no reference to other models.
     *
     * @param  array  $relations
     *
     * @return void
     */
    public function secureSoftDelete(array $relations): void
    {
        $hasRelation = $this->getModel()->query()
            ->where('id', $this->id)
            ->where(function ($query) use ($relations) {
                foreach ($relations as $relation) {
                    $query->orWhereHas($relation);
                }
            })
            ->withTrashed()
            ->exists();

        if ($hasRelation) {
            throw new RelationshipException();
        }

        if ($this->deleted_at) {
            $this->forceDelete();
            return;
        }
        $this->delete();
    }

    /**
     * Delete only when there is no reference to other models.
     *
     * @param  array  $relations
     *
     * @return void
     */
    public function secureDelete(array $relations, bool $forceDelete = false): void
    {
        $hasRelation = $this->getModel()->query()
            ->where('id', $this->id)
            ->where(function ($query) use ($relations) {
                foreach ($relations as $relation) {
                    $query->orWhereHas($relation);
                }
            })
            ->exists();

        if ($hasRelation) {
            throw new RelationshipException();
        }

        $this->forceDelete();
    }
}
