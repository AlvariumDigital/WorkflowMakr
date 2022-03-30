<?php

namespace AlvariumDigital\WorkflowMakr\Http\Controllers;

use AlvariumDigital\WorkflowMakr\Models\Action;
use AlvariumDigital\WorkflowMakr\Models\Scenario;
use AlvariumDigital\WorkflowMakr\Models\Transition;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
        if (request()->get('q')) {
            $query->where('designation', 'like', '%' . request()->get('q') . '%');
        }
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
     * @param int $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $action)
    {
        return response()->json(Action::where('id', $action)->first(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $action)
    {
        $action = Action::where('id', $action)->first();
        $validator = Validator::make($request->all(), [
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
     * @param int $action
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $action)
    {
        $action = Action::where('id', $action)->first();
        if (Transition::where('action_id', $action->id)->count() == 0) {
            $action->delete();
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'failed', 'message' => 'The action is used by an active transition'], 422);
    }
}
