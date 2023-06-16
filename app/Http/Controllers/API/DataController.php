<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class DataController extends Controller
{
    public function getData(): JsonResponse
    {

        $user = Auth::user();


        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $tasks = Task::where('user_id', $user->id)->with('subtasks')->get();

        return response()->json($tasks);
    }
}
