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
     *
     * @return void
     */
    public function makeATransition(int $transition_id, $performed_by): void;

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
