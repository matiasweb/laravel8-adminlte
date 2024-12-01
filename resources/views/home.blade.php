@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-primary">
            <div class="inner">
                <h3>150</h3>
                <p>Nuevas Órdenes</p>
            </div>
            <div class="icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <a href="#" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-success">
            <div class="inner">
                <h3>53<sup style="font-size: 20px">%</sup></h3>
                <p>Tasa de Conversión</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <a href="#" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-info">
            <div class="inner">
                <h3>44</h3>
                <p>Usuarios Registrados</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-gradient-danger">
            <div class="inner">
                <h3>65</h3>
                <p>Visitantes Únicos</p>
            </div>
            <div class="icon">
                <i class="fas fa-eye"></i>
            </div>
            <a href="#" class="small-box-footer">
                Más información <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <section class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ion ion-clipboard mr-1"></i>
                    Lista de Tareas
                </h3>
                <div class="card-tools">
                    <ul class="pagination pagination-sm">
                        <li class="page-item"><a href="#" class="page-link">«</a></li>
                        <li class="page-item"><a href="#" class="page-link">1</a></li>
                        <li class="page-item"><a href="#" class="page-link">2</a></li>
                        <li class="page-item"><a href="#" class="page-link">3</a></li>
                        <li class="page-item"><a href="#" class="page-link">»</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <ul class="todo-list" data-widget="todo-list">
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="todoCheck1">
                            <label for="todoCheck1"></label>
                        </div>
                        <span class="text">Que cada usuario tenga sus propias credenciales en Ajustes.</span>
                        <small class="badge badge-danger"><i class="far fa-clock"></i> 2 mins</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash"></i>
                        </div>
                    </li>
                    <li class="done">
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="todoCheck2" checked>
                            <label for="todoCheck2"></label>
                        </div>
                        <span class="text">Crear roles de usuario para distintos tipos de usuario (shopmanager, productmanager, siteadmin, superadmin, etc...)</span>
                        <small class="badge badge-info"><i class="far fa-clock"></i> 4 horas</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash"></i>
                        </div>
                    </li>
                    <li>
                        <span class="handle">
                            <i class="fas fa-ellipsis-v"></i>
                            <i class="fas fa-ellipsis-v"></i>
                        </span>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="todoCheck3">
                            <label for="todoCheck3"></label>
                        </div>
                        <span class="text">Hacer que el tema brille como una estrella</span>
                        <small class="badge badge-warning"><i class="far fa-clock"></i> 1 día</small>
                        <div class="tools">
                            <i class="fas fa-edit"></i>
                            <i class="fas fa-trash"></i>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Añadir tarea</button>
            </div>
        </div>
    </section>

    <section class="col-lg-5">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Estado de Carga</h3>
            </div>
            <div class="card-body">
                El contenido del cuerpo de la tarjeta.
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="ion ion-pie-graph mr-1"></i>
                    Estadísticas
                </h3>
            </div>
        </div>
    </section>
</div>
@stop

@section('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
    console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>
@stop