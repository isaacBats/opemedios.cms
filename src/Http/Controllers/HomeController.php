<?php

namespace Opemedios\Http\Controllers;


/*use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Opemedios\Http\Views\View;*/

use Illuminate\Http\Request;
use Opemedios\Http\Views\View;


//class HomeController extends Controller{
class HomeController {

	
	public function index(Request $request){

		/*$message = 'Hola';
		$view = new View('home', [
			'hola' => $message
		]);
		return $view->render();*/

		$view = new View('home', [
			'message' => 'Hello from a View'
		]);

		$response = $view->render();		
		$response->send();
		/*return 'hello at '.
			$request->getRequestUri().
			' from controller';*/
	}
}
