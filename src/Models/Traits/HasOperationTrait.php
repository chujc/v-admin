<?php

namespace ChuJC\Admin\Models\Traits;

use ChuJC\Admin\Facades\Admin;

trait HasOperationTrait
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booting()
    {
        static::creating(function ($model) {
            $model->created_by = Admin::user() ? Admin::user()->getKey() : 0;
        });
        static::updating(function ($model) {
            $model->updated_by = Admin::user() ? Admin::user()->getKey() : 0;
        });
    }
}
