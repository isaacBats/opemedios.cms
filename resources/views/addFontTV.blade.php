@extends('layout')

@section('title', 'Nueva noticia')

@section('content')
	<div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Agregar Fuente de Televisión</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Agregar Fuente
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form role="form" action="panel/font/add/font-tv/save" method="post" name="fontTV">
                            <div class="form-group">
                                <input class="form-control" placeholder="Nombre" name="nombre">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Empresa" name="empresa">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Conductor" name="conductor">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Canal" name="canal">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Horario" name="horario">
                                <p class="help-block">Example: 7:00 - 8:00 AM.</p>
                            </div>
                            <div class="form-group">
                                <label>Comentarios:</label>
                                <textarea class="form-control" name="comentario"></textarea>
                            </div>
                            <input name="enviar" type="submit" class="btn btn-default" value="Guardar">
                        </form>
                    </div>
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
@stop