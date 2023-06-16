@php

    $modalId = 'createSubtaskModal' . ($inputs['id'] ?? $task->id);

        if(isset($inputs)){

            $id = $inputs['id'];
            $task = $inputs['parent_id'];

        }else{
            $id = $task->id;
        }
        if ($task instanceof App\Models\Task) {
              $task = $task->id;
        }
@endphp
<div id="{{ $modalId }}" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCreateSubtaskModal({{$id}})">&times;</span>
        <h2>Создать подзадачу</h2>
        <form action="{{ route('subtask.create') }}" method="POST" id="createSubtaskForm">
            <input type="hidden" name="task_id" value="{{ $id }}">

            <input type="hidden" name="parent_subtask_id" value="{{ $task }}">
            @csrf
            <div>
                <label for="title">Заголовок:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="description">Описание:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div>
                <label for="status">Статус:</label>
                <select id="status" name="status" required>
                    <option value="TODO">TODO</option>
                </select>
            </div>
            <div>
                <label for="number">Приоритет:</label>
                <select id="number" name="number" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <button type="submit">Создать задание</button>
        </form>
    </div>
</div>


