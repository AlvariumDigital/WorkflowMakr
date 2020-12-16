<?php

namespace AlvariumDigital\WorkflowMakr\Http\Controllers;

use AlvariumDigital\WorkflowMakr\Models\History;
use AlvariumDigital\WorkflowMakr\Models\Transition;
use AlvariumDigital\WorkflowMakr\Helpers\Constants;
use AlvariumDigital\WorkflowMakr\Rules\TransitionUnique;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = Transition::query();
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
            'scenario_id' => 'required|exists:' . Constants::TABLES['SCENARIOS'] . ',id',
            'old_status_id' => 'nullable|exists:' . Constants::TABLES['STATUSES'] . ',id',
            'new_status_id' => [
                'required',
                'exists:' . Constants::TABLES['STATUSES'] . ',id',
                new TransitionUnique($request->get('old_status_id'), $request->get('new_status_id'), $request->get('scenario_id'), $request->get('action_id'))
            ],
            'action_id' => 'required|exists:' . Constants::TABLES['ACTIONS'] . ',id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $transition = new Transition();
        $transition->scenario_id = $request->get('scenario_id');
        $transition->old_status_id = $request->get('old_status_id');
        $transition->new_status_id = $request->get('new_status_id');
        $transition->action_id = $request->get('action_id');
        $transition->save();
        return response()->json($transition, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Transition $transition
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Transition $transition)
    {
        $transition = Transition::where('id', $transition->id)->with(['scenario', 'old_status', 'new_status', 'action'])->first();
        return response()->json($transition, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Transition $transition
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Transition $transition)
    {
        $validator = Validator::make($request->all(), [
            'old_status_id' => 'nullable|exists:' . Constants::TABLES['STATUSES'] . ',id',
            'new_status_id' => [
                'required',
                'exists:' . Constants::TABLES['STATUSES'] . ',id',
                new TransitionUnique($request->get('old_status_id'), $request->get('new_status_id'), $request->get('scenario_id'), $request->get('action_id'), $transition->id)
            ],
            'action_id' => 'required|exists:' . Constants::TABLES['ACTIONS'] . ',id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $transition->old_status_id = $request->get('old_status_id');
        $transition->new_status_id = $request->get('new_status_id');
        $transition->action_id = $request->get('action_id');
        $transition->save();
        return response()->json($transition, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Transition $transition
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Transition $transition)
    {
        if (History::where('transition_id', $transition->id)->count() == 0) {
            $transition->delete();
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'failed', 'message' => 'The transition is already used'], 422);
    }
}
