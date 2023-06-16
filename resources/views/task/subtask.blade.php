<li>
    <div>
        <!-- Отображение данных подзадачи -->
        <p>{{ $subtask->title }}</p>
        <p>{{ $subtask->description }}</p>
        <!-- Кнопка для создания подзадачи -->
        <button onclick="openCreateSubtaskModal({{ $subtask->id }})">Добавить подзадачу</button>
    </div>
    <!-- Рекурсивно отображать дочерние подзадачи -->
    @if ($subtask->children && $subtask->children->count() > 0)
        <ul>
            @foreach ($subtask->children as $childSubtask)
                @include('task.subtask', ['subtask' => $childSubtask])
            @endforeach
        </ul>
    @endif
</li>
