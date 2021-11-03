<?php

namespace AlvariumDigital\WorkflowMakr\Http\Controllers;

use AlvariumDigital\WorkflowMakr\Helpers\Constants;
use AlvariumDigital\WorkflowMakr\Models\Permission;
use AlvariumDigital\WorkflowMakr\Rules\PermissionUnique;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = Permission::query();
        $query->with(['children']);
        if (config('workflowmakr.pagination_size') == -1) {
            return response()->json($query->get(), 200);
        }
        return response()->json($query->paginate(config('workflowmakr.pagination_size')), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user_model = app()->make(config('workflowmakr.user_model'));
        $validator = Validator::make($request->json()->all(), [
            'user' => 'required|exists:' . $user_model->getTable() . ',' . $user_model->getKeyName(),
            'transitions' => 'required|array|min:1',
            'transitions.*' => 'required|exists:' . Constants::TABLES['TRANSITIONS'] . ',id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        Permission::where('user_id', $request->json()->get('user'))->delete();
        foreach ($request->json()->get('transitions') as $transition) {
            $permission = new Permission();
            $permission->user_id = $request->json()->get('user');
            $permission->transition_id = $transition;
            $permission->save();
        }
        return response()->json(Permission::where('user_id', $request->json()->get('user'))->get(), 200);
    }
}
