<?php 	

namespace App\Repositories;

use App\Region; //подключаем модель

class RegionRepository extends Repository {

	public function __construct(Region $Region){
		$this->model = $Region; //атрибуту класса Repository присваиваем объект класса Region
	}

}

 ?>