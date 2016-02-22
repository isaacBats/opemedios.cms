<?php 

namespace Opemedios\Infrastructure;

abstract class BaseRepository{

	private static $pdo;

	protected function getPDO(){

		return new \PDO(
			'mysql:host=localhost;dbname=opemedios', 
			'opemedios', 
			'opemedios'
		);
	}
}
