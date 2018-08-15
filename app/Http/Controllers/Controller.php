<?php

namespace App\Http\Controllers;
use App\Region;
use Config;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $region;
    	
    public function __construct(){

    }

    protected function getRegion(){

        $items = $this->region->get()->where('region', '!=', config('EnvSettings.TRANSLATE'));
        
        if($items->isEmpty()){
            return False;
        }
        return $items;
    }
    protected function renderOutput() {

    	return view($this->template)->with($this->vars);
    
    }
}
