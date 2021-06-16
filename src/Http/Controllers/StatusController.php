<?php

namespace AlvariumDigital\WorkflowMakr\Http\Controllers;

use AlvariumDigital\WorkflowMakr\Models\Status;
use AlvariumDigital\WorkflowMakr\Models\Transition;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = Status::query();
        if (request()->get('q')) {
            $query->where('designation', 'ilike', '%' . request()->get('q') . '%');
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
        $status = new Status();
        $status->code = $request->get('code');
        $status->designation = $request->get('designation');
        $status->save();
        return response()->json($status, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param Status $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Status $status)
    {
        return response()->json($status, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Status $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Status $status)
    {
        $validator = Validator::make($request->all(), [
            'designation' => 'required|max:255'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'form-validation-fails', 'messages' => $validator->getMessageBag()], 422);
        }
        $status->code = $request->get('code');
        $status->designation = $request->get('designation');
        $status->save();
        return response()->json($status, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Status $status
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Status $status)
    {
        if (Transition::where('old_status_id', $status->id)->orWhere('new_status_id', $status->id)->count() == 0) {
            $status->delete();
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['status' => 'failed', 'message' => 'The status is used by an active transition'], 422);
    }
}
