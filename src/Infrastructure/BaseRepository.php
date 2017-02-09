<?php 

namespace Opemedios\Infrastructure;

abstract class BaseRepository{

	private static $pdo;

	protected function getPDO(){

		if( !self::$pdo){

			self::$pdo = new \PDO(
				'mysql:host=localhost;dbname=opemedios', 
				'opemedios', 
				'opemedios'
			);			
		}

		return self::$pdo;
	}
}
