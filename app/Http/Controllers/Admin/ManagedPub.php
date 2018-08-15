<?php

namespace App\Http\Controllers\Admin;

use App\Location;
use App\TypeofPub;
use App\Buffer;
use App\Repositories\RegionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Config;

class ManagedPub extends Controller
{
	public function __construct(RegionRepository $regionModel){ 
        $this->region = $regionModel;  
    }

    public function showPage(){
    	$regions = $this->getRegion();
    	$Rgns = view(env('THEME').'.layouts.header')->with(['regions'=>$regions])->render();
    	$Pubs = Location::where('region', '=', env('REGION'))->get();
      $Types = TypeofPub::where('region', '=', env('REGION'))->get();
      $NVG = view(env('THEME').'.layouts.navigation')->render();
    	return view(env("THEME"). ".Admin.PubManager", ['region'=>$Rgns, 'Pubs' => $Pubs, 'types' => $Types, 'navigation'=>$NVG]);
    }






    /*AJAX*/

    public function deleteIMG(Request $request){
      return Response::json(unlink(storage_path('app/public/' . Config::get('settings.Pub_img_path') . "/" . $request->pub_img)));
    }

    public function addImg(Request $request){

      if($request->hasFile('imgPub')) {
          $file = $request->file('imgPub');
          $row = "-";
            $Location_empty = Location::get()->isEmpty();
            $Empty_buffer = $request->pub_img_name;

            if($request->type == "change"){
              if($request->buffer == "empty"){
                $file_name = $file->getClientOriginalName();
                $file->move(storage_path('app/public/'.Config::get('settings.Pub_img_path')),$file_name);
                $Answer = ['img_path' => url('storage') . "/Pub_img/", 'buffer'=>$file_name];
                return Response::json($Answer);
              }
              else{
                unlink(storage_path('app/public/' . Config::get('settings.Pub_img_path') . "/" . $request->buffer));
                $file_name = $file->getClientOriginalName();
                $file->move(storage_path('app/public/'.Config::get('settings.Pub_img_path')),$file_name);
                $Answer = ['img_path' => url('storage') . "/Pub_img/", 'buffer'=>$file_name];
                return Response::json($Answer);
              }
            }else{
            if($Location_empty != true){
                   $Last_location_id = Location::orderBy('created_at', 'desc')->first()->id;
                   if($request->pub_id != "empty"){
                     $id = $request->pub_id;
                     $row = $request->pub_id; 
                    }
                   else{
                      $id = $Last_location_id + 1;
                      $row = $id;
                      
                   }
                   if($Empty_buffer == "empty"){
                    $file_name = "" . $id . $file->getClientOriginalName();
                    $file->move(storage_path('app/public/'.Config::get('settings.Pub_img_path')),$file_name);

                    $Answer = ['img_path' => url('storage') . "/Pub_img/", 'img_name' => $file_name, 'pub_id' => $id, 'row'=>$row, 'buffer' =>"not empty"];
                    return Response::json($Answer);

                   }else{
                      unlink(storage_path('app/public/' . Config::get('settings.Pub_img_path') . "/" . $request->pub_img_name));
                      $file_name = "" . $id . $file->getClientOriginalName();
                      $file->move(storage_path('app/public/'.Config::get('settings.Pub_img_path')),$file_name);


                      $Answer = ['img_path' => url('storage') . "/Pub_img/", 'img_name' => $file_name, 'pub_id' => $id, 'row'=>$row, 'buffer' =>"not empty"];
                      return Response::json($Answer);
                   
                 }
                }
                else{
                  $id = 1;

                  if($Empty_buffer == "empty"){
                    $file_name = "" . $id . $file->getClientOriginalName();
                    $file->move(storage_path('app/public/'.Config::get('settings.Pub_img_path')),$file_name);

                    $Answer = ['img_path' => url('storage') . "/Pub_img/", 'img_name' => $file_name, 'pub_id' => $id];
                    return Response::json($Answer);

                   }else{
                      unlink(storage_path('app/public/' . Config::get('settings.Pub_img_path') . "/" . $request->pub_img_name));
                      $file_name = "" . $id . $file->getClientOriginalName();
                      $file->move(storage_path('app/public/'.Config::get('settings.Pub_img_path')),$file_name);
                    

                      $Answer = ['img_path' => url('storage') . "/Pub_img/", 'img_name' => $file_name, 'pub_id' => $id];
                      return Response::json($Answer);
                   
                 }
              }
            }
        }else{
          if($request->pub_id == "empty"){
            $Answer = ['img_path' => "empty", 'img_name' => "empty", 'pub_id' =>"empty", 'buffer' =>"empty"];
          }else{
            $Answer = ['img_path' => $request->pub_img_path, 'img_name' => $request->pub_img_name, 'pub_id' =>$request->pub_id, 'buffer' =>"empty"];
          }
        }
      
            
      return Response::json($Answer);
    }

   




    public function addLocation(Request $request){

      $location = new Location;
        $location->id = $request->Location_id;
      	$location->Location_name = $request->Location_name;
      	$location->Location_address = $request->Location_address;
      	$location->Location_map = $request->map;
      	$location->Location_img = $request->Location_img;
        $location->img_name = $request->img_name;
        $location->Location_type = $request->Location_type;
        $location->Location_description = $request->Location_description;
        $location->color = $request->color;
        $location->region = env('REGION');
    		$location->save();
    		$last_pub = Location::orderBy('created_at', 'desc')->first()->id;
    		
      return Response::json($last_pub);
    }
   


   public function getPubID($pub_id){
		  $pub = Location::find($pub_id);
    	return Response::json($pub);
   }


    public function addType(Request $request){
        $type = new TypeofPub;
        $type->Type_name = $request->project_name;
        $type->region = env('REGION');
        $type->save();
        $ID = TypeofPub::orderBy('created_at', 'desc')->first()->id;
        return Response::json($ID);
    }   

    public  function getTypeID($type_id){
        $type = TypeofPub::find($type_id);
        return $type;
    }

    public function deleteTypeID($type_id){

        $type = TypeofPub::destroy($type_id);
        return Response::json($type);
    }

    public function changeTypeID(Request $request, $type_id){

        $type = TypeofPub::find($type_id);
        $pubs = Location::where('Location_type', '=', $type->Type_name)->get();
        $pub_array = "";
        foreach ($pubs as $pub) {
          $pub_array .= "" . $pub->id . "/";
          $pub->Location_type = $request->Type_name;
          $pub->save();
        }
        $type->Type_name = $request->Type_name;
        $type->save();
        $data = ['type'=> $type, 'pub_array' => $pub_array];
        return Response::json($data);
    }

    public function getTypeofPub(){
      $types = TypeofPub::where('region', '=', env('REGION'))->get();
      return Response::json($types);
    }


    public function getSpecialPubID($pub_id){
      $pub = Location::find($pub_id);
      return Response::json($pub); 
    }

    public function changePubID(Request $request, $pub_id){
      $pub = Location::find($pub_id);

      
      $pub->Location_name = $request->Location_name;
      $pub->Location_address = $request->Location_address;
      $pub->Location_map = $request->map;
      $pub->Location_type = $request->Location_type;
      $pub->Location_description = $request->Location_description;
      $pub->color = $request->color;


      if($request->buffer != "empty"){
        unlink(storage_path('app/public/' . Config::get('settings.Pub_img_path') . "/" . $pub->img_name));
        Storage::move('public/' . Config::get('settings.Pub_img_path') . "/" . $request->buffer, 'public/' . Config::get('settings.Pub_img_path') . "/" . $pub_id . $request->buffer);
        $pub->img_name = "". $pub_id . $request->buffer; 
        $pub->Location_img = url('storage') . "/Pub_img/" . $pub_id . $request->buffer; 
      }else{
        $pub->Location_img = $request->Location_img;
        $pub->img_name = $request->img_name;
      }
      
      
      $pub->save();
      return Response::json($pub);
    }

    public function deletePubID($pub_id){
      $pub = Location::find($pub_id);
      $pub_img = unlink(storage_path('app/public/' . Config::get('settings.Pub_img_path') . "/" . $pub->img_name));
      $deletePub = Location::destroy($pub_id);
      return Response::json(['delete_pub'=>$deletePub, 'pub_img_delete'=>$pub_img]);
    }

    public function getallEllements(){
      $Location = Location::all();
      return Response::json(['Location'=>$Location]);
    }

    public function getEllements(Request $request){
      $stack = array();
      $counter=0;
      if(!empty($request->array))
        foreach($request->array as $item){
          $counter++;
          $pub = Location::find($item);
          array_push($stack, "pub".$counter, $pub);
        }
      else{
        $stack = ['empty'=>'empty'];
      }
      return Response::json(['stack'=>$stack, 'i'=>$counter]);
    }

    public function getAllPubs(){
      $pubs = Location::all();
      return Response::json($pubs);
    }


}
