<?php

namespace Opemedios\Http\Controllers;



use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Opemedios\Http\Views\View;


class HomeController extends Controller{

	
	public function index(Request $request){

		$view = new View('home', [
			'message' => 'Hello from a View'
		]);

		$response = $view->render();		
		$response->send();
	}
}
