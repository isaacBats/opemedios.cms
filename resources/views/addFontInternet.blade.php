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
                        <form role="form">
                            <div class="form-group">
                                <input class="form-control" placeholder="Nombre">
                                <p class="help-block">Example block-level help text here.</p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Empresa">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Conductor">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Canal">
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Horario">
                            </div>
                            <div class="form-group">
                                <label>File input</label>
                                <input type="file">
                            </div>
                            <div class="form-group">
                                <label>Text area</label>
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-default">Submit Button</button>
                            <button type="reset" class="btn btn-default">Reset Button</button>
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