<?php

namespace App\Services;

use App\Exceptions\RelationshipException;
use App\Interfaces\SecureDeleteContract;

class ModelService
{
    public static function softDelete(SecureDeleteContract $model, $constrains, $trans_key)
    {
        try {
            if ($constrains) {
                $model->secureSoftDelete($constrains);
            }
            $model->delete();
        } catch (RelationshipException) {
            return redirect()->back()
                ->withStatus(AlertResponseService::deletionHasRelation($trans_key));
        }
        return redirect()->back()
            ->withStatus(AlertResponseService::deletionSuccess($trans_key));
    }

    public static function delete(SecureDeleteContract $model, $constrains, $trans_key)
    {
        try {
            if ($constrains) {
                $model->secureDelete($constrains);
            }
            $model->forceDelete();
        } catch (RelationshipException) {
            return redirect()->back()
                ->withStatus(AlertResponseService::deletionHasRelation($trans_key));
        }
        return redirect()->back()
            ->withStatus(AlertResponseService::deletionSuccess($trans_key));
    }
}
