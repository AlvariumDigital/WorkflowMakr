<?php

namespace AlvariumDigital\WorkflowMakr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transition extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    public $incrementing = true;
    protected $table = 'workflow_makr_transitions';
    protected $primaryKey = 'id';

    protected $fillable = ['old_status_id', 'new_status_id', 'scenario_id', 'action_id', 'created_at', 'updated_at'];

    public function scenario(): BelongsTo
    {
        return $this->belongsTo(Scenario::class, 'scenario_id', 'id');
    }

    public function action(): BelongsTo
    {
        return $this->belongsTo(Action::class, 'action_id', 'id');
    }

    public function old_status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'old_status_id', 'id');
    }

    public function new_status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'new_status_id', 'id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class, 'transition_id', 'id');
    }
}
