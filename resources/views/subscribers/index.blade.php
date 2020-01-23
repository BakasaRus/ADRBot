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
                            <th scope="col" class="align-middle">VK ID</th>
                            <th scope="col" class="align-middle">
                                <abbr title="Мы не запрашиваем ФИО у пользователя, а берём его из профиля ВКонтакте">ФИО</abbr>
                            </th>
                            <th scope="col" class="align-middle">Пол</th>
                            <th scope="col" class="align-middle">Возраст</th>
                            <th scope="col" class="align-middle">Препарат</th>
                            <th scope="col" class="align-middle">Количество сообщений</th>
                            <th scope="col" class="align-middle">Дата последнего сообщения</th>
                            <th scope="col" class="align-middle">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($subscribers as $subscriber)
                        <tr>
                            <th scope="row" class="align-middle">
                                <a href="https://vk.com/id{{ $subscriber->id }}" target="_blank">
                                    {{ $subscriber->id }}
                                </a>
                            </th>
                            <th scope="row" class="align-middle">
                                <a href="{{ route('subscribers.show', ['subscriber' => $subscriber->id]) }}">
                                    {{ $subscriber->full_name }}
                                </a>
                            </th>
                            <td class="align-middle">{{ $subscriber->readable_sex }}</td>
                            <td class="align-middle">{{ $subscriber->age ?? '¯\_(ツ)_/¯' }}</td>
                            <td class="align-middle">{{ $subscriber->adr_drug ?? '¯\_(ツ)_/¯' }}</td>
                            <td class="align-middle">{{ $subscriber->messages_count }}</td>
                            <td class="text-nowrap align-middle">{{ $subscriber->last_message_at }}</td>
                            <td class="align-middle">
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
