<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Broadcast Test</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>--}}
</head>
<body>
    <div class="container">
        <h2>Текущие задачи</h2>
        <p class="text-muted loading">Загрузка...</p>
        <table class="table">
            <tbody class="active-tasks">
            </tbody>
        </table>
        <button class="btn btn-success new-task mb-4">Добавить задачу</button>

        <h2>Выполненные задачи</h2>
        <p class="text-muted loading">Загрузка...</p>
        <table class="table">
            <tbody class="completed-tasks">
            </tbody>
        </table>
    </div>
    <script src="/js/app.js"></script>
</body>
</html>
