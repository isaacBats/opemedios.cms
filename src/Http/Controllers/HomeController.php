<?php

namespace Opemedios\Http\Controllers;



use Illuminate\Http\Request;
use Opemedios\Http\Views\View;
use Opemedios\Http\Views\ViewBlade;


class HomeController extends BaseController{

	public function index(Request $request){

		$this->head();
		
		$view = new View('home', [
			'message' => 'Hello from a View'
		]);

		$response = $view->render();		
		$response->send();

		$this->footer();
	}

	public function hola(Request $request){

		$view = new ViewBlade();
		
		$renderer = $view->renderBlade();
		
		return $renderer->render('layout', [
			'message' => 'Hello from Blade'
		]);
	}
}
