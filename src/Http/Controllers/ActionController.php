<?php

namespace AlvariumDigital\WorkflowMakr\Http\Controllers;

use AlvariumDigital\WorkflowMakr\Models\Action;
use AlvariumDigital\WorkflowMakr\Helpers\Constants;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = Action::query();
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:50|unique:' . Constants::TABLES['ACTIONS'] . ',code,NULL,id,deleted_at,NULL',
            'designation' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $action = new Action();
        $action->code = $request->get('code');
        $action->designation = $request->get('designation');
        $action->save();
        return response()->json($action, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Action $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Action $action)
    {
        return response()->json($action, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Action $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Action $action)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:50|unique:' . Constants::TABLES['ACTIONS'] . ',code,' . $action->id . ',id,deleted_at,NULL',
            'designation' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $action->code = $request->get('code');
        $action->designation = $request->get('designation');
        $action->save();
        return response()->json($action, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Action $action
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Action $action)
    {
        $action->delete();
        return response()->json(['status' => 'success'], 200);
    }
}
