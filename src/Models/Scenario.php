<?php

namespace AlvariumDigital\WorkflowMakr\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scenario extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    public $incrementing = true;
    protected $table = 'workflow_makr_scenarios';
    protected $primaryKey = 'id';

    protected $fillable = ['designation', 'created_at', 'updated_at'];

    public function transitions(): HasMany
    {
        return $this->hasMany(Transition::class, 'scenario_id', 'id')->whereNull('predecessor_id');
    }
}
