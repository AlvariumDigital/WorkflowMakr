<?php

namespace AlvariumDigital\WorkflowMakr\Rules;

use AlvariumDigital\WorkflowMakr\Models\Transition;
use Illuminate\Contracts\Validation\Rule;

class TransitionUnique implements Rule
{
    private $old_status_id;
    private $new_status_id;
    private $scenario_id;
    private $action_id;
    private $transition_id;

    /**
     * Create a new rule instance.
     *
     * @param int $old_status_id The old status id
     * @param int $new_status_id The new status id
     * @param int $scenario_id The scenario id
     * @param int $action_id The action id
     * @param int $transition_id The transition id, if exists
     *
     * @return void
     */
    public function __construct($old_status_id, $new_status_id, $scenario_id, $action_id, $transition_id = null)
    {
        $this->old_status_id = $old_status_id;
        $this->new_status_id = $new_status_id;
        $this->scenario_id = $scenario_id;
        $this->action_id = $action_id;
        $this->transition_id = $transition_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $query = Transition::query();
        $query->where('old_status_id', $this->old_status_id)
            ->where('new_status_id', $this->new_status_id)
            ->where('scenario_id', $this->scenario_id)
            ->where('action_id', $this->action_id);
        if ($this->transition_id != null) {
            $query->where('id', '<>', $this->transition_id);
        }
        return $query->count() == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique', ['attribute' => 'transition']);
    }
}
