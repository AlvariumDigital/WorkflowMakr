<?php

namespace AlvariumDigital\WorkflowMakr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class History extends Model
{
    use SoftDeletes;

    public $timestamps = false;
    public $incrementing = true;
    protected $table = 'workflow_makr_histories';
    protected $primaryKey = 'id';

    protected $fillable = ['model', 'performed_by_model', 'performed_by', 'performed_at'];

    public function transition(): BelongsTo
    {
        return $this->belongsTo(Transition::class, 'transition_id', 'id');
    }

    public function getPerformerAttribute()
    {
        $performed_by_model = app($this->performed_by_model);
        return $performed_by_model->where($performed_by_model->getKeyName(), $this->performed_by)->first();
    }

    public function getModelObjectAttribute()
    {
        $model = app($this->model);
        return $model->where($model->getKeyName(), $this->model_id)->first();
    }
}
