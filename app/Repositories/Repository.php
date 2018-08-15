<?php 
	
namespace App\Repositories;

abstract class Repository {
	
	protected $model = FALSE; //определяем атрибут класса

	public function get(){
		$builder = $this->model->select('*'); //выбирвются все элементы из базы данных

		return $builder->get(); //получаем все элементы из базы данных 
	}
}

 ?>