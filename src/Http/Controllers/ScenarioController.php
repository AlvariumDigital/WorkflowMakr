<?php

namespace AlvariumDigital\WorkflowMakr\Http\Controllers;

use AlvariumDigital\WorkflowMakr\Helpers\Constants;
use AlvariumDigital\WorkflowMakr\Models\Scenario;
use AlvariumDigital\WorkflowMakr\Models\Status;
use AlvariumDigital\WorkflowMakr\Models\Transition;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ScenarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = Scenario::query();
        if (request()->get('q')) {
            $query->where('designation', 'LIKE', '%' . request()->get('q') . '%');
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
            'designation' => 'required|max:255',
            'entity' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $scenario = Scenario::create($request->only('designation', 'entity'));
        return response()->json($scenario, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $scenario
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $scenario)
    {
        $scenario = Scenario::where('id', $scenario)->with(['transitions', 'transitions.children'])->first();
        return response()->json($scenario, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $scenario
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $scenario)
    {
        $scenario = Scenario::where('id', $scenario)->first();
        $validator = Validator::make($request->all(), [
            'designation' => 'required|max:255',
            'entity' => 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $scenario->update(
            $request->only('designation', 'entity')
        );
        return response()->json($scenario, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $scenario
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $scenario)
    {
        $scenario = Scenario::where('id', $scenario)->first();
        if ($scenario->transitions()->count() == 0) {
            $scenario->delete();
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'failed', 'message' => 'The scenario contains at least an active transition'], 422);
    }
}
