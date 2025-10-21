@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Modifica corso #{{ $course->id }}</h1>

        <form method="POST" action="{{ route('courses.update', $course) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Titolo</label>
                <input name="title" class="form-control" value="{{ old('title', $course->title) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Descrizione</label>
                <textarea name="description" class="form-control">{{ old('description', $course->description) }}</textarea>
            </div>

            <button class="btn btn-primary">Salva</button>
        </form>
    </div>
@endsection
