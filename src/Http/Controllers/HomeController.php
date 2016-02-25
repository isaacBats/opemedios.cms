<?php

namespace Opemedios\Http\Controllers;



use Illuminate\Http\Request;
use Opemedios\Http\Views\View;


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
}
