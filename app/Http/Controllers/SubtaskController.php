<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use Termwind\Components\Dd;
use Illuminate\Contracts\View\View;

class SubtaskController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();
        $userId = Auth::id();

        $data['user_id'] = $userId;
        $subtask = Subtask::create([
            'parent_id' => $data['parent_subtask_id'],
            'title' => $data['title'],
            'status' => $data['status'],
            'number' => $data['number'],
            'user_id' => $data['user_id'],
            'description' => $data['description'],
            'data' => $data['task_id'],
        ]);

        //Тут могла-бы быть ваша реклама
        return redirect()->route('tasks.send', ['category_id' => $data['task_id'], 'task_id' => $data['parent_subtask_id']]);

    }

    public function getModal(Request $request)
    {
        $inputs = $request->all();
        return view('task/create_subtask_modal', compact('inputs'));
    }

    public function send(Request $request, int $category_id = null): View
    {

        if ($request->task_id == null) {
            $category_id = $request->category_id;
            $request->task_id = $request->category_id;
        } else {

            $category_id = $request->task_id;
        }

        $categories = Subtask::with(['childs'])->where('data', $category_id)->get();
        $task = Task::with('subtasks')->find($request->task_id);

        return view('task/task', compact('categories', 'task'));
    }


    public function delete(Request $request)
    {
        $subtaskId = $request->task_id;


        $hasTodoSubtasks = $this->hasTodoSubtasks($subtaskId);

        // Проверка статуса задачи
        $task = Subtask::find($subtaskId);
        if ($task->status === 'TODO' || $hasTodoSubtasks) {
            return response()->json(['error' => 'Невозможно удалить задачу с подзадачами со статусом "TODO"'], 400);
        }

        // Удаление подзадачи
        $this->deleteSubtask($subtaskId);

        return redirect()->route('tasks.send', ['task_id' => $request['previous_data']]);
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
        // Проверка наличия подзадач со статусом

        // Если есть подзадачи со статусом
        if ($hasTodoSubtasks) {
            return response()->json(['error' => 'Подзадачи со статусом "TODO" присутствуют'], 400);
        }

        // Обновление данных подзадачи
        $subtask = Subtask::find($taskId);
        $subtask->status = $request->status;
        $subtask->save();

        // Возвращаем успешный ответ
        return response()->json(['success' => 'Статус обновлен'], 200);
    }

}
