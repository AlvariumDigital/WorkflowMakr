<?php

namespace AlvariumDigital\WorkflowMakr\Helpers;

/**
 * Interface Constants
 * ***************************************************************
 * This interface contains all the helper constants used by the
 * package itself.
 * ***************************************************************
 *
 * @package AlvariumDigital\WorkflowMakr\Helpers
 */
interface Constants
{

    /**
     * The package tables names
     */
    const TABLES = [
        "ACTIONS" => "workflow_makr_actions",
        "STATUSES" => "workflow_makr_statuses",
        "SCENARIOS" => "workflow_makr_scenarios",
        "TRANSITIONS" => "workflow_makr_transitions",
        "HISTORIES" => "workflow_makr_histories"
    ];

}
