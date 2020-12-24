<?php

namespace AlvariumDigital\WorkflowMakr\Helpers;

use AlvariumDigital\WorkflowMakr\Models\Status;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Interface WorkflowMakr
 * ***************************************************************
 * This interface must be implemented by all the models using this
 * package.
 * ***************************************************************
 *
 * @package AlvariumDigital\WorkflowMakr\Helpers
 */
interface WorkflowMakr
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
    public function makeATransition(int $transition_id, $performed_by, $performed_by_model = null): void;

    /**
     * Rollback a transition
     */
    public function transitionRollback(): void;

    /**
     * Return the transitions history of the model
     *
     * @return HasMany
     */
    public function histories(): HasMany;

    /**
     * Return the possible transitions based on the model current status and scenario id
     *
     * @return array
     */
    public function getNextTransitionsAttribute(): array;

    /**
     * Return the model's linked workflow scenario id
     * If 0, no scenario will be linked
     *
     * @return int
     */
    public function linkedScenario(): int;

    /**
     * Get the current status of the model
     *
     * @return Status|null
     */
    public function getStatusAttribute();

    /**
     * Get the transition performer model class name
     *
     * @return string
     */
    public function transition_performer(): string;

}
