<?php

namespace App\Http\Controllers;

use App\Api;
use App\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json(Response::transform(Api::get(), "OK", true), 200);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required|min:10',
            'photo' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array(
                'message' => 'check your request again. desc must be 10 char or more and form must be filled', 'status' => false), 400);
        } else {
            $photo = $request->file('photo');
            $extension = $photo->getClientOriginalExtension();
            Storage::disk('public')->put($photo->getFilename() . '.' . $extension, File::get($photo));
            $api = new Api();
            $api->name = $request->name;
            $api->description = $request->description;
            $api->photo = "uploads/" . $photo->getFilename() . '.' . $extension;
            $api->save();

            return response()->json(
                Response::transform(
                    $api, 'successfully created', true
                ), 201);
        }
    }

    public function show($id)
    {
        $api = Api::find($id);
        if (is_null($api)) {
            return response()->json(array('message' => 'record not found', 'status' => false), 200);
        }
        return response()->json(Response::transform($api, "found", true), 200);
    }

    public function update(Request $request, $id){
        //$retrieve = $request->all();
        $api = Api::find($id);
        if($api != null){
            if($request->file('photo') != null){
                $photo = $request->file('photo');
                $extension = $photo->getClientOriginalExtension();
                Storage::disk('public')->put($photo->getFilename().'.'.$extension,  File::get($photo));
                $api->photo = "uploads/".$photo->getFilename().'.'.$extension;
            }
            if($request->name != null){$api->name = $request->name;}
            if($request->description != null){ $api->description = $request->description; }
            $api->id = $id;
            $api->save();
            return response() -> json(array('message'=>'Success update', 'status'=>false),200);

//            return response()->json(Response::transform($bug, "Successfully updated", true), 201);
        }else{
            return response() -> json(array('message'=>'Cannot update because record not found', 'status'=>false),200);
        }
    }

    public function destroy($id){
        $api = Api::find($id);
        if(is_null($api)){
            return response() -> json(array('message'=>'cannot delete because record not found', 'status'=>false),200);
        }
        Api::destroy($id);
        return response() -> json(array('message'=>'succesfully deleted', 'status' => false), 200);
    }

    public function search(Request $request){
        $query = $request->search;
        $api = Api::where('name','LIKE','%'.$query.'%')->get();
        if(sizeof($api) > 0){
            return response() -> json(Response::transform($api,"Has found", true), 200);
        }else{
            return response() -> json(array('message'=>'No record found', 'status' => false), 200);
        }
    }
}
