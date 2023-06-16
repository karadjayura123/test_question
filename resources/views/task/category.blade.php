<div class="details-container">
    @foreach ($tree as $row)
        <details>
            <summary>{{ $row->title }}</summary>
            <div class="task-block">
                <form id="form-{{ $row->id }}" class="form-action" data-action-delete="{{ route('subtask.delete', $row->id) }}" data-action-update="{{ route('subtask.update', $row->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="{{ $row->status }}">
                    <input type="hidden" name="task_id" value="{{ $row->id }}">
                    <input type="hidden" name="data" value="{{ $row->data }}">
                    @if (!empty($row->parent_id))
                        <input type="hidden" name="previous_data" value="{{ $row->parent_id }}">
                    @endif
                    <div>
                        <p><strong>Заголовок:</strong> {{ $row->title }}</p>
                        <p><strong>Описание:</strong> {{ $row->description }}</p>
                    </div>
                    <div class="task-info">
                        <select name="status" id="" class="u12-branch">
                            <option value="{{ $row->status }}">{{ $row->status }}</option>
                            <option value="TODO">TODO</option>
                            <option value="DONE">DONE</option>
                        </select>
                        <p><strong>Приоритет:</strong> {{ $row->number }}</p>
                        <p><strong>Дата создания:</strong> {{ $row->created_at }}</p>
                        <p><strong>Дата обновления:</strong> {{ $row->updated_at }}</p>
                    </div>
                    <button type="submit" name="action" value="delete" id="delete-btn-{{ $row->id }}">Удалить</button>
                    <button type="submit" name="action" value="update" id="update-btn-{{ $row->id }}">Изменить</button>
                </form>
                <button type="button" onclick="openCreateSubtaskModal_({{ $row->id }}, {{ $row->parent_id }})">Добавить подзадачу</button>
            </div>

            @if (isset($row->childs))
                @include('task.category', ['tree' => $row->childs])
            @endif
        </details>
    @endforeach
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let successMessage = '{{ session('success') }}';
        if (successMessage) {
            toastr.success(successMessage);
        }
    });

    function closeCreateSubtaskModal() {
        document.getElementById('createSubtaskModal').style.display = 'none';
    }
    function openCreateSubtaskModal_(previousTaskId) {
        $.ajax({
            url: '/getModal',
            data: {
                id: previousTaskId,
                parent_id: {{ $row->parent_id }}
            },
            type: "GET",
            dataType: 'html',
            async: false,
            success: function (response) {
                $('#getModal').html(response);
                $('#createSubtaskModal' + previousTaskId).css("display", "block");
            }
        })
    }

        const forms = document.querySelectorAll('.form-action');

        forms.forEach((form) => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const action = this.querySelector('button[type="submit"]:focus').value;
                let formAction = '';

                if (action === 'delete') {
                    formAction = this.getAttribute('data-action-delete');
                } else if (action === 'update') {
                    formAction = this.getAttribute('data-action-update');
                }

                const taskId = this.querySelector('input[name="task_id"]').value;
                const formData = new FormData(this);

                fetch(formAction, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (response.ok) {
                            return response.json();
                        } else {
                            throw new Error('Ошибка при обновлении статуса');
                        }
                    })
                    .then(data => {
                        // Обработка успешного ответа от сервера
                        // ...

                        // Пример перезагрузки страницы
                        location.reload();
                    })
                    .catch(error => {
                        // Обработка ошибки
                        console.error(error);
                    });
            });
        });

</script>
