<?php

namespace AlvariumDigital\WorkflowMakr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    public $incrementing = true;
    protected $table = 'workflow_makr_actions';
    protected $primaryKey = 'id';

    protected $fillable = ['code', 'designation', 'created_at', 'updated_at'];

    public function transitions(): HasMany
    {
        return $this->hasMany(Transition::class, 'action_id', 'id');
    }
}
