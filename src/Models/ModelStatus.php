<?php

namespace AlvariumDigital\WorkflowMakr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelStatus extends Model
{
    public $timestamps = true;
    public $incrementing = true;
    protected $table = 'workflow_makr_model_statuses';
    protected $primaryKey = 'id';

    public function getModelObjectAttribute()
    {
        $model = app($this->model);
        return $model->where($model->getKeyName(), $this->model_id)->first();
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id', 'id');
    }
}
