<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExecuteQueryRequest;
use App\Http\Services\QueryService;
use Exception;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    /**
     * Show the application form
     */
    public function index()
    {
        return view('query');
    }

    /**
     * Execute the query
     */
    public function show(ExecuteQueryRequest $request)
    {
        try {
            $validated = $request->validated();
            $queryService = new QueryService();
            $result = $queryService->query($validated['sql_query']);

            session()->flash('query', $validated['sql_query']);
            session()->flash('result', $result);
            return redirect()->back();

        } catch (Exception $exception) {

            return redirect()->back()->withErrors($exception->getMessage());
        }
    }
}