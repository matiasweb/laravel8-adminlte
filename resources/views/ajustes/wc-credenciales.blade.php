@extends('adminlte::page')

@section('title', 'Ajustes > Conexión a WooCommerce')

@section('content_header')
<h1>Conexión a WooCommerce</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="p-4 sm:p-8 bg-{{ Auth::user()->theme }} shadow sm:rounded-lg mb-3">
            <h3>Credenciales</h3>

            {{-- Alertas de éxito y error --}}
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-check"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="icon fa fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
            @endif

            {{-- Estado de conexión --}}
            <div id="connection-status" style="margin-top: 20px;"></div>

            {{-- Formulario de credenciales --}}
            <form action="{{ route('ajustes.wc.guardar') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="url">URL del sitio</label>
                    <input type="url" name="url" class="form-control" id="url" value="{{ old('url', $url) }}" required>
                </div>
                <div class="form-group">
                    <label for="consumer_key">Consumer Key</label>
                    <input type="text" name="consumer_key" class="form-control" id="consumer_key" value="{{ old('consumer_key', $consumer_key) }}" required>
                </div>
                <div class="form-group">
                    <label for="secret_key">Secret Key</label>
                    <input type="text" name="secret_key" class="form-control" id="secret_key" value="{{ old('secret_key', $secret_key) }}" required>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-success" id="verify-connection"><i class="fas fa-sync"></i> Verificar Conexión</button>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>

    <div class="col-md-6">

    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/section_ajustes.css">
@stop

@section('js')
<script>
    document.getElementById('verify-connection').addEventListener('click', async function (e) {
        e.preventDefault();

        // Capturar los datos del formulario
        const url = document.getElementById('url').value;
        const consumerKey = document.getElementById('consumer_key').value;
        const secretKey = document.getElementById('secret_key').value;

        const statusDiv = document.getElementById('connection-status');

        try {
            // Verificar la conexión
            const verifyResponse = await fetch('{{ route('ajustes.wc.verificar') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    url: url,
                    consumer_key: consumerKey,
                    secret_key: secretKey,
                }),
            });

            const responseText = await verifyResponse.text();
            let verifyData;
            let isJson = false;

            try {
                verifyData = JSON.parse(responseText);
                isJson = true;
            } catch (e) {
                isJson = false;
                verifyData = responseText;
            }

            if (verifyResponse.ok && isJson && verifyData.message === 'Conexión exitosa') {
                console.log('Conexión exitosa, procediendo a guardar.');

                // Guardar la configuración
                const saveResponse = await fetch('{{ route('ajustes.wc.guardar') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        url: url,
                        consumer_key: consumerKey,
                        secret_key: secretKey,
                    }),
                });

                if (saveResponse.ok) {
                    statusDiv.innerHTML = `
                        <div class="alert alert-info alert-dismissible fade show mt-3">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="fas fa-save"></i>
                            Conexión exitosa y configuración guardada correctamente.
                        </div>`;
                } else {
                    statusDiv.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show mt-3">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <i class="icon fa fa-exclamation-triangle"></i>
                            Error al guardar la configuración.
                        </div>`;
                }
            } else {
                // Mostrar error de conexión
                let errorMessage = 'Conexión fallida.';

                if (isJson) {
                    errorMessage = verifyData.error || verifyData.message || errorMessage;
                }

                statusDiv.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <i class="icon fa fa-exclamation-triangle"></i>
                        ${errorMessage}
                    </div>`;
            }
        } catch (error) {
            console.error('Error en la solicitud:', error);
            // Mostrar error general
            statusDiv.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <i class="icon fa fa-exclamation-triangle"></i>
                    Error: ${error.message}
                </div>`;
        }
    });
</script>
@stop
