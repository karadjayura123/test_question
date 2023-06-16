<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

</head>
<body>
<x-app-layout>

    @if(session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <button id="createTaskButton" class="fixed-button">Создать задание</button>

    <div id="createTaskModal" class="modal">
        <div class="modal-content">
            <span id="closeModal" class="close">&times;</span>
            <form action="{{ route('tasks.create') }}" method="POST">
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
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <form id="filterForm" action="{{ route('tasks.filter') }}" method="POST">
            @csrf
            <input type="text" id="filterTitle" name="filterTitle" placeholder="Фильтр по заголовку">
            <select id="filterStatus" name="filterStatus">
                <option value="">Фильтр по статусу</option>
                <option value="TODO">TODO</option>
                <option value="DONE">DONE</option>
            </select>
            <select id="filterPriority" name="filterPriority">
                <option value="">Фильтр по приоритету</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <button id="applyFiltersButton" style="text-align: center; color: white; type="submit">Применить фильтры</button>
        </form>
    <h2 style="text-align: center; color: white;font-size: 30px;">Список задач</h2>
    <div class="task-container dark:bg-gray-800">
        @foreach ($tasks as $task)
            <div class="task-block">
                <form action="{{ route('tasks.send') }}" method="POST">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <div>
                        <p><strong>Заголовок:</strong> {{ $task->title }}</p>
                        <p><strong>Описание:</strong> {{ $task->description }}</p>
                    </div>
                    <div class="task-info">
                        <p><strong>Статус:</strong> {{ $task->status }}</p>
                        <p><strong>Приоритет:</strong> {{ $task->number }}</p>
                        <p><strong>Дата создания:</strong> {{ $task->created_at }}</p>
                        <p><strong>Дата обновления:</strong> {{ $task->updated_at }}</p>
                    </div>
                    <button type="submit">Изменить</button>
                </form>
            </div>
        @endforeach
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        const createTaskButton = document.getElementById('createTaskButton');
        const createTaskModal = document.getElementById('createTaskModal');
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

            const applyFiltersButton = document.getElementById('applyFiltersButton');
            applyFiltersButton.addEventListener('click', () => {
                const filterTitle = document.getElementById('filterTitle').value;
                const filterStatus = document.getElementById('filterStatus').value;
                const filterPriority = document.getElementById('filterPriority').value;

                // Получить CSRF-токен из метатега
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Отправить запрос на сервер с помощью AJAX и включить CSRF-токен
                $.ajax({
                    url: '{{ route('tasks.filter') }}', // Замените на соответствующий URL-адрес вашего серверного обработчика
                    method: 'POST',
                    data: {
                        filterTitle: filterTitle,
                        filterStatus: filterStatus,
                        filterPriority: filterPriority,
                        _token: csrfToken // Включить CSRF-токен
                    },
                    success: function(response) {
                        // Обработать полученные данные
                        // и обновить содержимое списка задач на основе фильтрации
                        // например, обновить элемент с классом 'task-container'
                    },
                    error: function(xhr, status, error) {
                        // Обработать ошибку, если не удалось получить данные с сервера
                    }
                });
            });
        });

    </script>
</x-app-layout>
</body>
</html>
