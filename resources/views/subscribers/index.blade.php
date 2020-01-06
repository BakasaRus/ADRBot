@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <h5 class="card-header">Список опрошенных</h5>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th scope="col">VK ID</th>
                        <th scope="col">
                            <abbr title="Мы не запрашиваем ФИО у пользователя, а берём его из профиля ВКонтакте">ФИО</abbr>
                        </th>
                        <th scope="col">Пол</th>
                        <th scope="col">Возраст</th>
                        <th scope="col">Количество сообщений</th>
                        <th scope="col">Дата последнего сообщения</th>
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
                        <td>{{ $subscriber->messages_count }}</td>
                        <td>¯\_(ツ)_/¯</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
