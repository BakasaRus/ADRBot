@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <h5 class="card-header">Список опрошенных</h5>
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
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
                            <a href="{{ route('subscribers.show', ['subscriber' => $subscriber->id]) }}">
                                {{ $subscriber->name }} {{ $subscriber->surname }}
                            </a>
                        </th>
                        <td>{{ $subscriber->sex }}</td>
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
