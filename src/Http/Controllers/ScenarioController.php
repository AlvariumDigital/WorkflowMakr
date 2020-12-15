<?php

namespace AlvariumDigital\WorkflowMakr\Http\Controllers;

use AlvariumDigital\WorkflowMakr\Helpers\Constants;
use AlvariumDigital\WorkflowMakr\Models\Scenario;
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
            'code' => 'required|max:50|unique:' . Constants::TABLES['SCENARIOS'] . ',code,NULL,id,deleted_at,NULL',
            'designation' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $scenario = new Scenario();
        $scenario->code = $request->get('code');
        $scenario->designation = $request->get('designation');
        $scenario->save();
        return response()->json($scenario, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Scenario $scenario
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Scenario $scenario)
    {
        $scenario = Scenario::where('id', $scenario->id)->with(['transitions'])->first();
        return response()->json($scenario, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Scenario $scenario
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Scenario $scenario)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:50|unique:' . Constants::TABLES['SCENARIOS'] . ',code,' . $scenario->id . ',id,deleted_at,NULL',
            'designation' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $scenario->code = $request->get('code');
        $scenario->designation = $request->get('designation');
        $scenario->save();
        return response()->json($scenario, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Scenario $scenario
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Scenario $scenario)
    {
        if (Transition::where('scenario_id', $scenario->id)->count() == 0) {
            $scenario->delete();
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'failed', 'message' => 'The scenario contains at least an active transition'], 422);
    }
}
