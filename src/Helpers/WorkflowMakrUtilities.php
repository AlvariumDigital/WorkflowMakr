<?php

namespace AlvariumDigital\WorkflowMakr\Helpers;

use AlvariumDigital\WorkflowMakr\Exceptions\TransitionNotAuthorized;
use AlvariumDigital\WorkflowMakr\Exceptions\TransitionRollbackNotAuthorized;
use AlvariumDigital\WorkflowMakr\Models\History;
use AlvariumDigital\WorkflowMakr\Models\ModelStatus;
use AlvariumDigital\WorkflowMakr\Models\Status;
use AlvariumDigital\WorkflowMakr\Models\Transition;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

/**
 * Trait WorkflowMakrUtilities
 * ***************************************************************
 * This trait class must be used by all the models implementing
 * the WorkflowMakr interface  so they can use all the features
 * offered by the package.
 * ***************************************************************
 *
 * @package AlvariumDigital\WorkflowMakr\Helpers
 */
trait WorkflowMakrUtilities
{
    /**
     * Make a transition to the model
     *
     * @param int $transition_id The transition id
     * @param mixed $performed_by The transition performer primary key value
     * @param string|null $performed_by_model The transition performer model class to override the default value
     *
     * @return void
     */
    public function makeATransition(int $transition_id, $performed_by, $performed_by_model = null): void
    {
        try {
            $transition = Transition::where('id', $transition_id)->firstOrFail();
            if (
                ($this->status == null && $transition->old_status_id == null)
                || ($this->status != null && $this->status->id == $transition->old_status_id)
            ) {
                $model = app(get_class());

                // Save or update the model's current status
                $modelStatus = ModelStatus::where('model', get_class())->where('model_id', $this->{$model->getKeyName()})->first();
                if ($modelStatus == null) {
                    $modelStatus = new ModelStatus();
                }
                $modelStatus->model = get_class();
                $modelStatus->model_id = $this->{$model->getKeyName()};
                $modelStatus->status_id = $transition->new_status_id;
                $modelStatus->save();

                // Insert a new transition history for this model
                $history = new History();
                $history->model = get_class();
                $history->model_id = $this->{$model->getKeyName()};
                $history->performed_by_model = $performed_by_model ?? $this->transition_performer();
                $history->performed_by = $performed_by;
                $history->transition_id = $transition->id;
                $history->performed_at = Carbon::now();
                $history->save();
            } else {
                throw new TransitionNotAuthorized('The transition selected is not authorized for this model');
            }
        } catch (ModelNotFoundException|TransitionNotAuthorized $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Get the transition performer model class name
     *
     * @return string
     */
    public function transition_performer(): string
    {
        return config('workflowmakr.default_transition_performer');
    }

    /**
     * Rollback a transition
     */
    public function transitionRollback(): void
    {
        if ($this->status) {
            $query = History::where('model', get_class())->where('model_id', $this->{$this->getKeyName()})->orderBy('performed_at', 'DESC');
            $history = $query->first();
            if ($history->transition->new_status_id == $this->status->id) {
                $history->delete();
                $history = $query->first();
            }
            $model = app(get_class());
            $modelStatus = ModelStatus::where('model', get_class())->where('model_id', $this->{$model->getKeyName()})->first();
            if ($modelStatus == null) {
                $modelStatus = new ModelStatus();
            }
            $modelStatus->model = get_class();
            $modelStatus->model_id = $this->{$model->getKeyName()};
            $modelStatus->status_id = $history->transition->new_status_id;
            $modelStatus->save();
        } else {
            throw new TransitionRollbackNotAuthorized('The model has an empty status, cannot rollback');
        }
    }

    /**
     * Return the transitions history of the model
     *
     * @return HasMany
     */
    public function histories(): HasMany
    {
        $model = app(get_class());
        return $this->hasMany(History::class, 'model_id', $model->getKeyName())->where('model', get_class());
    }

    /**
     * Return the possible transitions based on the model current status and scenario id
     */
    public function getNextTransitionsAttribute(): array
    {
        if ($this->linkedScenario()) {
            $query = Transition::query();
            $query->where('scenario_id', $this->linkedScenario());
            if ($this->status) {
                $query->where('old_status_id', $this->status->id);
            } else {
                $query->whereNull('old_status_id');
            }
            return $query->get();
        }
        return collect([]);
    }

    /**
     * Return the model's linked workflow scenario id
     * If 0, no scenario will be linked
     *
     * @return int
     */
    public function linkedScenario(): int
    {
        return 0;
    }

    /**
     * Get the current status of the model
     *
     * @return Status|null
     */
    public function getStatusAttribute()
    {
        $model = app(get_class());
        $modelStatus = ModelStatus::where('model', get_class())->where('model_id', $this->{$model->getKeyName()})->first();
        if ($modelStatus != null) {
            return $modelStatus->status;
        }
        return null;
    }
}
