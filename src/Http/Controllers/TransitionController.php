<?php

namespace AlvariumDigital\WorkflowMakr\Http\Controllers;

use AlvariumDigital\WorkflowMakr\Helpers\Constants;
use AlvariumDigital\WorkflowMakr\Models\Action;
use AlvariumDigital\WorkflowMakr\Models\History;
use AlvariumDigital\WorkflowMakr\Models\Status;
use AlvariumDigital\WorkflowMakr\Models\Transition;
use AlvariumDigital\WorkflowMakr\Rules\TransitionUnique;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $query->with(['scenario']);
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
        $validator = Validator::make($request->json()->all(), [
            'scenario_id' => 'required|exists:' . Constants::TABLES['SCENARIOS'] . ',id',
            'old_status' => 'nullable',
            'new_status' => 'required',
            'action' => 'required|exists:' . Constants::TABLES['ACTIONS'] . ',id',
            'predecessor_id' => 'nullable|exists:' . Constants::TABLES['TRANSITIONS'] . ',id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $old_status = null;
        if ($request->json()->get('old_status')) {
            $old_status = Status::whereRaw('LOWER(`designation`) = ?', [Str::lower($request->json()->get('old_status'))])->first();
            if ($old_status == null) {
                $old_status = new Status();
                $old_status->designation = $request->json()->get('old_status');
                $old_status->save();
            }
        }
        $new_status = Status::whereRaw('LOWER(`designation`) = ?', [Str::lower($request->json()->get('new_status'))])->first();
        if ($new_status == null) {
            $new_status = new Status();
            $new_status->designation = $request->json()->get('new_status');
            $new_status->save();
        }
        $action = Action::whereRaw('LOWER(`designation`) = ?', [Str::lower($request->json()->get('action'))])->first();
        if ($action == null) {
            $action = new Status();
            $action->designation = $request->json()->get('action');
            $action->save();
        }
        $validator = Validator::make(['new_status' => $new_status->id], [
            'new_status' => [
                new TransitionUnique($old_status == null ? null : $old_status->id, $new_status->id, $request->json()->get('scenario_id'), $action->id)
            ]
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $transition = new Transition();
        $transition->scenario_id = $request->json()->get('scenario_id');
        $transition->old_status_id = $old_status == null ? null : $old_status->id;
        $transition->new_status_id = $new_status->id;
        $transition->action_id = $action->id;
        $transition->predecessor_id = $request->json()->get('predecessor_id');
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
        $transition = Transition::where('id', $transition->id)->with(['scenario'])->first();
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
        $validator = Validator::make($request->json()->all(), [
            'old_status' => 'nullable',
            'new_status' => 'required',
            'action' => 'required',
            'predecessor_id' => 'nullable|exists:' . Constants::TABLES['TRANSITIONS'] . ',id'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $old_status = null;
        if ($request->json()->get('old_status')) {
            $old_status = Status::whereRaw('LOWER(`designation`) = ?', [Str::lower($request->json()->get('old_status'))])->first();
            if ($old_status == null) {
                $old_status = new Status();
                $old_status->designation = $request->json()->get('old_status');
                $old_status->save();
            }
        }
        $new_status = Status::whereRaw('LOWER(`designation`) = ?', [Str::lower($request->json()->get('new_status'))])->first();
        if ($new_status == null) {
            $new_status = new Status();
            $new_status->designation = $request->json()->get('new_status');
            $new_status->save();
        }
        $action = Action::whereRaw('LOWER(`designation`) = ?', [Str::lower($request->json()->get('action'))])->first();
        if ($action == null) {
            $action = new Status();
            $action->designation = $request->json()->get('action');
            $action->save();
        }
        $validator = Validator::make(['new_status' => $new_status->id], [
            'new_status' => [
                new TransitionUnique($old_status == null ? null : $old_status->id, $new_status->id, $request->json()->get('scenario_id'), $action->id, $transition->id)
            ]
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $transition->old_status_id = $old_status == null ? null : $old_status->id;
        $transition->new_status_id = $new_status->id;
        $transition->action_id = $action->id;
        $transition->predecessor_id = $request->json()->get('predecessor_id');
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
        $transitions = $this->transitionIdsToDelete($transition);
        if (History::whereIn('transition_id', $transitions)->count() == 0) {
            Transition::whereIn('id', $transitions)->delete();
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'failed', 'message' => 'The transition is already used'], 422);
    }

    /**
     * Get transitions and children (recursively) IDs to delete
     * @param int|Transition $transition
     * @return array
     */
    private function transitionIdsToDelete($transition)
    {
        if (!$transition instanceof Transition) {
            $transition = Transition::where('id', $transition)->first();
        }
        $results = [];
        array_push($results, $transition->id);
        foreach ($transition->children as $child) {
            foreach ($this->transitionIdsToDelete($child->id) as $t) {
                array_push($results, $t);
            }
        }
        return $results;
    }
}
