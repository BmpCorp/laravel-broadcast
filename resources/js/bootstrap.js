window.$ = require('jquery');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import Task from './task';

window.io = require('socket.io-client');

const echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001',
});

axios.get('/api/task').then((response) => {
    $('.loading').remove();

    const { active, completed } = response.data;

    if (active) {
        const taskHtml = active.map(Task.getTemplate);
        $('.active-tasks').append(taskHtml);
    }

    if (completed) {
        const taskHtml = completed.map(Task.getTemplate);
        $('.completed-tasks').append(taskHtml);
    }
});

// Добавить
$('.new-task').on('click', function () {
    const name = prompt('Введите название: ', 'Новая задача');

    if (!!name) {
        axios.post('/api/task', {
            name: name,
        }).then((response) => {
            const { task } = response.data;
            Task.show(task);
        });
    }
});

// Отменить выполнненной/невыполненной
$('body').on('change', '.task__complete', function () {
    const $taskRow = $(this).parents('.task');
    const id = $taskRow.data('id');
    const isComplete = $(this).is(':checked');

    axios.post(`/api/task/${id}/complete`, {
        is_complete: isComplete ? 1 : 0,
    }).then((response) => {
        $taskRow.detach().appendTo(isComplete ? '.completed-tasks' : '.active-tasks');
    });
});

// Изменить название
$('body').on('click', '.task__edit', function () {
    const $taskRow = $(this).parents('.task');
    const id = $taskRow.data('id');
    const newName = prompt('Введите новое название: ', $taskRow.find('.task__name').text());

    if (!!newName) {
        axios.post(`/api/task/${id}/update`, {
            name: newName,
        }).then((response) => {
            $taskRow.find('.task__name').text(newName);
        });
    }
});

// Удалить
$('body').on('click', '.task__delete', function () {
    const $taskRow = $(this).parents('.task');
    const id = $taskRow.data('id');

    if (confirm('Вы действительно хотите удалить задачу?')) {
        axios.post(`/api/task/${id}/delete`).then((response) => {
            $taskRow.remove();
        });
    }
});

// Обработка событий
echo.channel('tasks')
    .listen('.task-monitor', (event) => {
        switch (event.type) {
            case 'add': case 'update':
                Task.show(event);
                break;

            case 'remove':
                Task.remove(event.id);
                break;
        }
    });
