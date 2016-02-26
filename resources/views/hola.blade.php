@extends('layout')

@section('content')
    <p>This is my body content. Of HOLA</p>
    <div>
    	<ul>
    		<li>Uno</li>
    		<li>Dos</li>
    		<li>Tres</li>
    	</ul>
    </div>

    <p>{{ $message }}</p>
@stop