<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{{ asset('css/custom-login.css') }}">
        <title>Login</title>
    </head>
    @section('content_header')
        @if(session('success'))
            <div class="alert alert-success" id="message" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('danger'))
            <div class="alert alert-danger" id="message" role="alert">
                {{ session('danger') }}
            </div>
        @endif
    @endsection
    <body>
        <h1 class="login-title"></h1>
        
        <form method="POST" action="{{ route('login') }}" class="container">
            @csrf
            <h2>Login</h2>
            <section class="input-box" request>
                <input type="text" name="cpf" placeholder="Digite o CPF" />
                <i class="bx bxs-user"></i>
                @error('cpf')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </section>
            
            <section class="input-box" request>
                <input type="password" name="password" placeholder="Digite a Senha" />
                <i class="bx bxs-lock-alt"></i>
                @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                @enderror
            </section>
            
            
            {{-- <section class="remember-forgot-box">
                <div class="remember-me">
                    <input type="checkbox" name="remember" id="remember" />
                    <label for="remember" class="form-check-label">
                        <h5>Lembre de mim</h5>
                    </label>
                </div>
            </section> --}}

            <button class="login-button" type="submit">Login</button>
            <br>
            <h5 class="dont-have-an-account">
                Não tem uma conta?
                <a href="{{ route('register') }}"><b>Registre-se</b></a>
            </h5>
            
        </form>
    </body>
    </html>
    
    
{{--    @extends('layouts.app')
    @section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="cpf" class="col-md-4 col-form-label text-md-end">{{ __('CPF') }}</label>

                            <div class="col-md-6">
                                <input id="cpf" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" required autocomplete="cpf" autofocus>

                                @error('cpf')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="cargo" class="col-md-4 col-form-label text-md-end">{{ __('Centro de Custo') }}</label>
                            <div class="col-md-6">
                                <div class="custom-select-wrapper">
                                    <select class="bustom-select" id="cargo" name="cargo" request>
                                        <option value="" disabled selected>Escolha uma opção</option>
                                        <option value="0">Responsável pelo setor</option>
                                        <option value="1">Colaborador comum</option>
                                    </select>
                                </div>
                                @error('cargo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Lembre-se de mim') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                                
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Esqueceu sua senha?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
