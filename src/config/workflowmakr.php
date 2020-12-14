<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination size
    |--------------------------------------------------------------------------
    |
    | This value is the size of all paginate() function results.
    | This value is used by all the index() function of the workflow makr
    | resources. You can modify this value to change the size of the pagination
    | results, or type -1 to disable paginations
    |
    */

    'pagination_size' => 20,

    /*
    |--------------------------------------------------------------------------
    | Routes middleware
    |--------------------------------------------------------------------------
    |
    | This value is an array containing the middleware used to protect all
    | the package API routes.
    | By default, the only middleware used is the "api" middleware, but it
    | should contains also the middleware to give access to your appropriate
    | users.
    |
    */

    'routes_middleware' => ['api'],

    /*
    |--------------------------------------------------------------------------
    | Default transition performer
    |--------------------------------------------------------------------------
    |
    | This value is the class name for the default transition performer.
    | This value will be used every time a new transition is made by the
    | package to a model implementing the WorkflowMakrUtilities.
    | You can override this value globally by changing this value, or override
    | the function transition_performer() in a specific model.
    |
    */

    'default_transition_performer' => \App\Models\User::class,

];
