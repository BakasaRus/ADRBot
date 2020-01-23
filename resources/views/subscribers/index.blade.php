@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <h5 class="card-header">
                Список опрошенных
                <small class="text-secondary">Всего {{ $subscribers->count() }}</small>
            </h5>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">VK ID</th>
                            <th scope="col">
                                <abbr title="Мы не запрашиваем ФИО у пользователя, а берём его из профиля ВКонтакте">ФИО</abbr>
                            </th>
                            <th scope="col">Пол</th>
                            <th scope="col">Возраст</th>
                            <th scope="col">Препарат</th>
                            <th scope="col">Количество<br>сообщений</th>
                            <th scope="col">Дата последнего<br>сообщения</th>
                            <th scope="col">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($subscribers as $subscriber)
                        <tr>
                            <th scope="row">
                                <a href="https://vk.com/id{{ $subscriber->id }}" target="_blank">
                                    {{ $subscriber->id }}
                                </a>
                            </th>
                            <th scope="row">
                                <a href="{{ route('subscribers.show', ['subscriber' => $subscriber->id]) }}">
                                    {{ $subscriber->full_name }}
                                </a>
                            </th>
                            <td>{{ $subscriber->readable_sex }}</td>
                            <td>{{ $subscriber->age ?? '¯\_(ツ)_/¯' }}</td>
                            <td>{{ $subscriber->adr_drug ?? '¯\_(ツ)_/¯' }}</td>
                            <td>{{ $subscriber->messages_count }}</td>
                            <td>{{ $subscriber->last_message_at }}</td>
                            <td>
                                <a type="button" class="btn btn-sm btn-danger" href="{{ route('subscribers.show', ['subscriber' => $subscriber->id]) }}" data-toggle="tooltip" title="Удалить со всеми сообщениями" onclick="event.preventDefault(); document.getElementById('del_{{ $subscriber->id }}').submit();">Удалить</a>
                                <form method="POST" action="{{ route('subscribers.show', ['subscriber' => $subscriber->id]) }}" id="del_{{ $subscriber->id }}"  style="display: none;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
