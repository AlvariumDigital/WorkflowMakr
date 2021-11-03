<?php

namespace AlvariumDigital\WorkflowMakr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    public $incrementing = true;
    protected $table = 'workflow_makr_permissions';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'transition_id', 'created_at', 'updated_at'];

    public function transition(): BelongsTo
    {
        return $this->belongsTo(Transition::class, 'transition_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('workflowmakr.user_model'), 'user_id', 'id');
    }
}
