@extends('adminlte::page')

@section('title', 'Pedidos de WooCommerce')

@section('content_header')
<h1>Pedidos de WooCommerce</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <form class="form-inline" action="{{ route('pedidos.index') }}" method="GET">
            <div class="form-group mr-2">
                <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Buscar">

            </div>
            <div class="form-group mr-2">
                <select name="status" class="form-control">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="on-hold" {{ request('status') == 'on-hold' ? 'selected' : '' }}>En espera</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
    </div>

    <div class="card-body">
        <!-- Indicador de Carga -->
        <div id="loading" class="text-center my-3" style="display: none;">
            <div class="spinner-border" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>

        <!-- Contenedor de la Tabla para poder ocultarla -->
        <div id="orders-table" class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th class="d-none d-md-table-cell">Estado</th>
                        <th class="d-none d-md-table-cell">Total</th>
                        <th class="d-none d-md-table-cell">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                    @php
                    // Definir clases de color basadas en el estado
                    $statusColorMap = [
                    'Pendiente' => 'btn-warning',
                    'Procesando' => 'btn-primary',
                    'En espera' => 'btn-secondary',
                    'Completado' => 'btn-success',
                    'Cancelado' => 'btn-danger',
                    'Reembolsado' => 'btn-info',
                    'Fallido' => 'btn-danger',
                    ];
                    $colorClass = isset($statusColorMap[$order['estado']]) ? $statusColorMap[$order['estado']] : 'btn-secondary';

                    // Lista de todos los estados posibles
                    $allStatuses = [
                    'Pendiente' => 'pending',
                    'Procesando' => 'processing',
                    'En espera' => 'on-hold',
                    'Completado' => 'completed',
                    'Cancelado' => 'cancelled',
                    'Reembolsado' => 'refunded',
                    'Fallido' => 'failed',
                    ];
                    @endphp
                    <tr data-widget="expandable-table" aria-expanded="false">
                        <td>{{ $order['id'] }}</td>
                        <td>{{ $order['nombre'] }}</td>
                        <td class="d-none d-md-table-cell">
                            <div class="dropdown">
                                <button class="btn {{ $colorClass }} dropdown-toggle btn-sm" type="button" id="dropdownMenuButton{{ $order['id'] }}" data-bs-toggle="dropdown" aria-expanded="false" onclick="event.stopPropagation()">
                                    {{ $order['estado'] }}
                                </button>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $order['id'] }}" onclick="event.stopPropagation()">
                                    <li><a class="dropdown-item" href="{{ url('/pedidos/' . $order['id']) }}">Ver pedido</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    @foreach ($allStatuses as $statusName => $statusValue)
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/pedidos/' . $order['id'] . '/cambiar-estado/' . $statusValue) }}">
                                            Cambiar estado a {{ $statusName }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </td>
                        <td class="d-none d-md-table-cell">{{ $order['total'] }}</td>
                        <td class="d-none d-md-table-cell">{{ $order['fecha'] }}</td>
                    </tr>
                    <tr class="expandable-body d-none">
                        <td colspan="5">
                            <div class="p-3">
                                <strong>Detalles del Pedido:</strong>
                                <p>
                                    <!-- Campos visibles en todos los dispositivos -->
                                    <span class="d-block"><strong>Email:</strong> {{ $order['email'] }}</span>
                                    <span class="d-block"><strong>Método de Pago:</strong> {{ $order['metodo_pago'] }}</span>

                                    <!-- Campos adicionales visibles solo en dispositivos móviles -->
                                    <span class="d-block d-md-none"><strong>ID:</strong> {{ $order['id'] }}</span>
                                    <span class="d-block d-md-none"><strong>Nombre:</strong> {{ $order['nombre'] }}</span>
                                    <span class="d-block d-md-none"><strong>Estado:</strong> {{ $order['estado'] }}</span>
                                    <span class="d-block d-md-none"><strong>Total:</strong> {{ $order['total'] }}</span>
                                    <span class="d-block d-md-none"><strong>Fecha:</strong> {{ $order['fecha'] }}</span>
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div id="paginacion-table" class="mt-3">
            <p>{{ $totalWooOrders }} elementos - Página {{ $currentPage }} de {{ $totalPages }}</p>

            <ul class="pagination justify-content-center">
                <!-- Botón para ir a la primera página -->
                @if ($currentPage > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ route('pedidos.index', array_merge(request()->all(), ['page' => 1])) }}" aria-label="Primera Página">
                        &laquo;
                    </a>
                </li>
                @endif

                <!-- Botón Anterior -->
                @if ($currentPage > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ route('pedidos.index', array_merge(request()->all(), ['page' => $currentPage - 1])) }}" aria-label="Página Anterior">
                        Anterior
                    </a>
                </li>
                @endif

                <!-- Botón Siguiente -->
                @if ($currentPage < $totalPages)
                    <li class="page-item">
                    <a class="page-link" href="{{ route('pedidos.index', array_merge(request()->all(), ['page' => $currentPage + 1])) }}" aria-label="Página Siguiente">
                        Siguiente
                    </a>
                    </li>
                    @endif

                    <!-- Botón para ir a la última página -->
                    @if ($currentPage < $totalPages)
                        <li class="page-item">
                        <a class="page-link" href="{{ route('pedidos.index', array_merge(request()->all(), ['page' => $totalPages])) }}" aria-label="Última Página">
                            &raquo;
                        </a>
                        </li>
                        @endif
            </ul>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    @media (max-width: 576px) {
        .table-responsive {
            font-size: 0.9rem;
        }
    }

    .expandable-body td {
        background-color: #f9f9f9;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        // Inicializar la funcionalidad de tabla expandible de AdminLTE
        $('[data-widget="expandable-table"]').ExpandableTable();

        // Manejar el evento de envío del formulario
        $('form').on('submit', function() {
            // Ocultar la tabla
            $('#orders-table').hide();
            $('#paginacion-table').hide();
            // Mostrar el spinner de carga
            $('#loading').show();
        });

        // Ocultar el spinner y mostrar la tabla al cargar la página
        $(window).on('load', function() {
            $('#loading').hide();
            $('#orders-table').show();
        });
    });
</script>
@stop