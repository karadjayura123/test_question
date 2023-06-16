<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</head>
<body>
<x-app-layout>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
        <h2 style="text-align: center;color: white;font-size: 30px;">Список задач</h2>
    <div class="task-container dark:bg-gray-800">
        <!-- Display main task -->
        <div class="task-block dark-bg">
            @if ($task)
                <form action="{{ route('tasks.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">

                    <div class="form-group">
                        <label class="label" for="title">Заголовок:</label>
                        <input type="text" id="title" name="title" value="{{ $task->title }}" required>
                    </div>

                    <div class="form-group">
                        <label class="label" for="description">Описание:</label>
                        <textarea id="description" name="description" required>{{ $task->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="label" for="status">Статус:</label>
                        <select id="status" name="status" required>
                            <option value="TODO" {{ $task->status === 'TODO' ? 'selected' : '' }}>TODO</option>
                            <option value="DONE" {{ $task->status === 'DONE' ? 'selected' : '' }}>DONE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="label" for="number">Приоритет:</label>
                        <select id="number" name="number" required>
                            <option value="1" {{ $task->number === '1' ? 'selected' : '' }}>1</option>
                            <option value="2" {{ $task->number === '2' ? 'selected' : '' }}>2</option>
                            <option value="3" {{ $task->number === '3' ? 'selected' : '' }}>3</option>
                            <option value="4" {{ $task->number === '4' ? 'selected' : '' }}>4</option>
                            <option value="5" {{ $task->number === '5' ? 'selected' : '' }}>5</option>
                        </select>
                    </div>

                    <button type="button" onclick="openCreateSubtaskModal({{ $task->id }})">Добавить подзадачу</button>

                    <button type="submit">Сохранить изменения</button>
                </form>

                <form action="{{ route('task.delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $task->status }}">
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <button type="submit">Удалить</button>
                </form>
            @endif
        </div>
    </div>
        <ul>
            @if($categories->isNotEmpty())
            <div>
                @include('task.category', ['tree' => $categories])
            </div>
            @endif
        </ul>
        @if($task !== null)
             @include('task.create_subtask_modal')
        @endif
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        const createTaskButton = document.getElementById('createTaskButton');
        const createTaskModal = document.getElementById('createSubtaskModal');
        const closeModalButton = document.getElementById('closeModal');

        createTaskButton.addEventListener('click', () => {
            createTaskModal.style.display = 'block';
        });

        closeModalButton.addEventListener('click', () => {
            createTaskModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === createTaskModal) {
                createTaskModal.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            let successMessage = '{{ session('success') }}';
            if (successMessage) {
                toastr.success(successMessage);
            }

        });
        function openCreateSubtaskModal(parentSubtaskId) {
            let modalId = 'createSubtaskModal' + parentSubtaskId;
            document.getElementById(modalId).style.display = 'block';
        }

        function closeCreateSubtaskModal(id) {
            let modalId = 'createSubtaskModal' + id;
            $('#getModal').html('');
            document.getElementById(modalId).style.display = 'none';
        }
        $.ajax({
            url: '/tasks/send',
            method: 'GET',
            data: { task_id: task_id },
            success: function(response) {
                if (response.reload) {
                    $('body').html(response.html);
                }
            },
            error: function(error) {
                // Обработка ошибки
            }
        });
    </script>
</x-app-layout>
<div id="getModal">

</div>
</body>
</html>
