@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-3">
            <h5 class="card-header">Информация об опрошенном</h5>
            <div class="card-body subscriber-info">
                <h5>Диагноз</h5>
                <p>{{ $subscriber->diagnosis ?? 'Не указан' }}</p>
                <h5>Описываемое лекарство</h5>
                <p>{{ $subscriber->adr_drug ?? 'Не указано' }}</p>
                <h5>Прочие лекарства</h5>
                <p>{{ $subscriber->other_drugs ?? 'Не указаны' }}</p>
                <h5>Нежелательные лекарственные реакции</h5>
                <p>{{ $subscriber->adr ?? 'Не указаны' }}</p>
                <h5>Факторы, влияющие на возникновение НЛР</h5>
                <p>{{ $subscriber->risks ?? 'Не указаны' }}</p>
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">Переписка опрошенного с ботом</h5>
            <table class="table mb-0">
                <thead>
                <tr>
                    <th scope="col">Автор</th>
                    <th scope="col">Текст</th>
                    <th scope="col">Дата</th>
                </tr>
                </thead>
                <tbody>
                @foreach($subscriber->messages as $message)
                    <tr>
                        <th scope="row">{{ $message->from ? 'Пользователь' : 'Бот' }}</th>
                        <td style="white-space: pre-line">{{ $message->text }}</td>
                        <td>{{ $message->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
