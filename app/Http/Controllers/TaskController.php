<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subtask;
use Psr\Log\NullLogger;

class TaskController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id; // Получение идентификатора текущего пользователя
        $tasks = Task::where('user_id', $userId)->get();

        return view('dashboard', compact('tasks'));
    }


    public function create(Request $request)
    {
        try {
            $data = $request->all();
            $userId = Auth::id();

            $data['user_id'] = $userId;

            $task = Task::insertData($data);

            return redirect()->route('dashboard')->with(['success' => 'Задача успешно создана']);


        } catch (\Exception $e) {

            return redirect()->back()->with('error', 'Произошла ошибка при создании задачи');
        }
    }
    public function filterTasks(Request $request)
    {
        $filterTitle = $request->input('filterTitle');
        $filterStatus = $request->input('filterStatus');
        $filterPriority = $request->input('filterPriority');

        // Примените фильтры к вашему запросу для получения соответствующих данных
        $tasks = Task::where(function ($query) use ($filterTitle) {
            if (!empty($filterTitle)) {
                $query->where('title', 'LIKE', '%'.$filterTitle.'%');
            }
        })->where(function ($query) use ($filterStatus) {
            if (!empty($filterStatus)) {
                $query->where('status', $filterStatus);
            }
        })->where(function ($query) use ($filterPriority) {
            if (!empty($filterPriority)) {
                $query->where('number', $filterPriority);
            }
        })->get();



        return view('dashboard', compact('tasks'));
    }

    public function hasTodoSubtasks($taskId)
    {
        $subtasks = Subtask::where('data', $taskId)->get();

        foreach ($subtasks as $subtask) {
            if ($subtask->status === 'TODO') {
                return true;
            }

            if ($this->hasTodoSubtasks($subtask->id)) {
                return true;
            }
        }

        return false;
    }

    public function update(Request $request)
    {

        $taskId = $request->task_id;
        $hasTodoSubtasks = $this->hasTodoSubtasks($taskId);

        if ($hasTodoSubtasks) {
            if($request->status == 'DONE') {
                return redirect()->back()->with('error', 'Произошла ошибка при создании задачи');
            }
        }

        // Обновление данных подзадачи
        $subtask = task::find($taskId);
        $subtask->title = $request->title;
        $subtask->description = $request->description;
        $subtask->status = $request->status;
        $subtask->number = $request->number;
        $subtask->save();

        // Возвращаем успешный ответ
        return redirect()->route('tasks.send', ['category_id' => $taskId]);
    }

    private function deleteSubtask($subtaskId)
    {
        $subtask = Subtask::find($subtaskId);

        if ($subtask) {
            $subtask->delete();

            // Рекурсивное удаление дочерних подзадач
            foreach ($subtask->childs as $child) {
                $this->deleteSubtask($child->id);
            }
        }
    }

    public function delete(Request $request)
    {
        $subtaskId = $request->task_id;

        $hasTodoSubtasks = $this->hasTodoSubtasks($subtaskId);

        // Проверка статуса задачи
        $task = Subtask::where('parent_id', $subtaskId)->get();



        if($task !== Null) {

            if (isset($task[0]->status) === 'TODO' || $hasTodoSubtasks) {
                return redirect()->route('tasks.send', ['category_id' => $subtaskId]);
            }
        }

        if($request->status === 'DONE')
        {
            return redirect()->route('tasks.send', ['category_id' => $subtaskId]);
        }


        $this->deleteSubtask($subtaskId);


        Task::destroy($subtaskId);

        return redirect()->route('dashboard');
    }

}
