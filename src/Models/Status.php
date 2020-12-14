<?php

namespace AlvariumDigital\WorkflowMakr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    public $incrementing = true;
    protected $table = 'workflow_makr_statuses';
    protected $primaryKey = 'id';

    protected $fillable = ['code', 'designation', 'created_at', 'updated_at'];

    public function transitions_starts_with(): HasMany
    {
        return $this->hasMany(Transition::class, 'old_status_id', 'id');
    }

    public function transitions_ends_with(): HasMany
    {
        return $this->hasMany(Transition::class, 'new_status_id', 'id');
    }
}
