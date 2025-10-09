<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function query(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string',
        ]);
        $bannedWords = ['INSERT', 'DELETE', 'UPDATE', 'DROP', 'ALTER', 'CREATE', 'REPLACE', 'TRUNCATE', 'EXEC'];
        foreach ($bannedWords as $word) {
            if (stripos($validated['query'], $word) !== false) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Query contains banned words.',
                    'data' => null,
                ], 400);
            }
        }
        $query = $request->input('query');
        try {
            $result = DB::select($query);
            return response()->json([
                'status' => 200,
                'message' => 'Query executed successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Error executing query: ' . $e->getMessage(),
                'data' => null,
            ], 400);
        }
    }
}
