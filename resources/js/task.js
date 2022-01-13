import $ from 'jquery';

class Task {
    show = (task) => {
        const $existing = $(`.task[data-id=${task.id}]`);
        if ($existing.length > 0) {
            $existing.remove();
        }

        $(task.is_complete ? '.completed-tasks' : '.active-tasks').append(this.getTemplate(task));
    };

    remove = (id) => {
        $(`.task[data-id=${id}]`).remove();
    };

    getTemplate = (task) => {
        return `<tr class="task row" data-id="${task.id}">
                    <td class="col-1">
                        <div class="form-check">
                            <input class="form-check-input task__complete" type="checkbox"${task.is_complete ? ' checked' : ''}>
                        </div>
                    </td>
                    <td class="col-7 task__name">${task.name}</td>
                    <td class="col-2">
                        <button type="button" class="btn btn-primary task__edit">Изменить</button>
                    </td>
                    <td class="col-2">
                        <button type="button" class="btn btn-danger task__delete">Удалить</button>
                    </td>
                </tr>`;
    };
}

export default new Task();
