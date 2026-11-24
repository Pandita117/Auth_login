@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Verificación en Dos Pasos (Administrador)</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <p class="text-muted">
                        Hemos enviado un código de 6 dígitos a tu correo electrónico. Tienes 5 minutos para ingresarlo antes de que el enlace expire.
                    </p>

                    <form method="POST" action="{{ route('admin.autenti.verify') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="two_factor_code" class="form-label">Código de verificación</label>
                            <input
                                id="two_factor_code"
                                type="text"
                                inputmode="numeric"
                                class="form-control form-control-lg text-center @error('two_factor_code') is-invalid @enderror"
                                name="two_factor_code"
                                value="{{ old('two_factor_code') }}"
                                placeholder="000000"
                                autofocus
                            >

                            @error('two_factor_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Verificar e ingresar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection