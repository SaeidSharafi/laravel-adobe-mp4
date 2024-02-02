<?php

namespace App\Services;

class AlertResponseService
{
    public const TYPE_ERROR   = "danger";
    public const TYPE_SUCCESS = "success";
    public const TYPE_INFO    = "info";
    public const TYPE_WARNING = "warning";
    public const TYPE_DARK    = "dark";

    public const ICON_DELETED = "icon_deleted";
    public const ICON_FAILED  = "icon_failed";
    public const ICON_SUCCESS = "icon_success";
    public const ICON_INFO    = "icon_info";

    public static function alert($msg, $icon = self::ICON_SUCCESS, $color = self::TYPE_SUCCESS)
    {
        return [
            'icon'  => $icon,
            'msg'   => $msg,
            'color' => $color
        ];
    }

    public static function success($msg)
    {
        return [
            'icon'  => self::ICON_SUCCESS,
            'msg'   => $msg,
            'color' => self::TYPE_SUCCESS
        ];
    }

    public static function error($msg)
    {
        return [
            'icon'  => self::ICON_FAILED,
            'msg'   => $msg,
            'color' => self::TYPE_ERROR
        ];
    }

    public static function info($msg)
    {
        return [
            'icon'  => self::ICON_INFO,
            'msg'   => $msg,
            'color' => self::TYPE_INFO
        ];
    }

    public static function warning($msg)
    {
        return [
            'icon'  => self::ICON_INFO,
            'msg'   => $msg,
            'color' => self::TYPE_WARNING
        ];
    }
    public static function createSuccess($model)
    {
        return [
            'icon'  => self::ICON_SUCCESS,
            'msg'   => __('response.create.success', ['model' => __("response.models.{$model}")]),
            'color' => self::TYPE_SUCCESS
        ];
    }

    public static function createFail($model)
    {
        return [
            'icon'  => self::ICON_FAILED,
            'msg'   => __('response.create.failed', ['model' => __("response.models.{$model}")]),
            'color' => self::TYPE_ERROR
        ];
    }
    public static function updateSuccess($model)
    {
        return [
            'icon'  => self::ICON_SUCCESS,
            'msg'   => __('response.update.success', ['model' => __("response.models.{$model}")]),
            'color' => self::TYPE_SUCCESS
        ];
    }

    public static function updateFail($model)
    {
        return [
            'icon'  => self::ICON_FAILED,
            'msg'   => __('response.update.success', ['model' => __("response.models.{$model}")]),
            'color' => self::TYPE_ERROR
        ];
    }
    public static function deletionSuccess($model)
    {
        return [
            'icon' => self::ICON_DELETED,
            'msg'  => __(
                "response.delete.success",
                ['model' => __("response.models.{$model}")]
            ),
            'color' => self::TYPE_SUCCESS
        ];
    }

    public static function deletionFailed($model)
    {
        return [
            'icon' => self::ICON_FAILED,
            'msg'  => __(
                "response.delete.failed",
                ['model' => __("response.models.{$model}")]
            ),
            'color' => self::TYPE_ERROR
        ];
    }

    public static function deletionHasRelation($model)
    {
        return [
            'icon' => self::ICON_INFO,
            'msg'  => __(
                "response.delete.has_relation",
                ['model' => __("response.models.{$model}")]
            ),
            'color' => self::TYPE_WARNING
        ];
    }
}
