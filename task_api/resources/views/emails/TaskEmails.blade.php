<x-mail::message>
    # {{$task->title}}

    {{$task->description}}

    Due Date: {{$task->due_date}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
