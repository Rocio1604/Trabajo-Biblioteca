@extends('layout.menu')

@section('title', 'Panel Inicio')
@section('content')
<div class="container-80 d-flex flex-wrap">
    <!-- prestamos recientes -->
    <div class="col-12 col-md-6 p-3">
        <h3>Bibliotecas más activas</h3>
        @foreach($bibliotecasPrestamos as $biblio)
            <div class="mb-3 p-3 border rounded">
                <h5>{{ $biblio->nombre }}</h5>
                <p><strong>Provincia:</strong> {{ $biblio->provincia }}</p>
                <p><strong>Socios activos:</strong> {{ $biblio->socios_count }}</p>
                <p><strong>Préstamos:</strong> {{ $biblio->prestamos_count }}</p>
            </div>
        @endforeach
    </div>
    <!-- bibliotecas más activas -->
    <div class="col-12 col-md-6 p-3">
        <h3>Préstamos recientes</h3>
        @foreach($prestamosRecientes as $prestamo)
            <div class="mb-3 p-3 border rounded">
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}</p>
                <p><strong>Socio:</strong> {{ $prestamo->socio_nombre }}</p>
                <p><strong>Libro:</strong> {{ $prestamo->libro_titulo }}</p>
            </div>
        @endforeach
    </div>
</div>

@endsection
@section('scripts')

@endsection