@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white fw-bold">Registrar nuevo equipo</div>
                <div class="card-body">
                    <x-equipo-form />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection