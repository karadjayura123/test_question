<div class="task-block dark-bg">
    <form action="{{ route('tasks.update') }}" method="POST">
        @csrf
        <input type="hidden" name="task_id" value="{{ $task->id }}">

        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input type="text" id="title" name="title" value="{{ $task->title }}" required>
        </div>

        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea id="description" name="description" required>{{ $task->description }}</textarea>
        </div>

        <div class="form-group">
            <label for="status">Статус:</label>
            <select id="status" name="status" required>
                <option value="TODO" {{ $task->status === 'TODO' ? 'selected' : '' }}>TODO</option>
            </select>
        </div>

        <div class="form-group">
            <label for="number">Приоритет:</label>
            <select id="number" name="number" required>
                <option value="1" {{ $task->number === '1' ? 'selected' : '' }}>1</option>
                <option value="2" {{ $task->number === '2' ? 'selected' : '' }}>2</option>
                <option value="3" {{ $task->number === '3' ? 'selected' : '' }}>3</option>
                <option value="4" {{ $task->number === '4' ? 'selected' : '' }}>4</option>
                <option value="5" {{ $task->number === '5' ? 'selected' : '' }}>5</option>
            </select>
        </div>

        @if($task->children !== NULL)
        @if ($task->children->count() > 0)
            <ul>
                @foreach ($task->children as $childTask)
                    @include('task_item', ['task' => $childTask])
                @endforeach
            </ul>
        @endif
        @endif

        <button type="submit">Сохранить изменения</button>
    </form>
</div>
