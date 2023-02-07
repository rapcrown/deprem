<?php

namespace App\Http\Controllers;

use App\Models\map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Cache\Factory;


class MapController extends Controller
{

    public function showMap(Factory $cache,Request $request)
    {


        $data = Cache::get('map', function() {
            return  $data = map::get();
        });



        $locations = [];

        foreach ($data as $key => $value) {

            $data = json_decode($value->data);


            $locations[] = array("id" => $value->id,"lat" => $data->loc[0],"lng" => $data->loc[1],"label" => $data->formatted_address);

        }


        return view("show-map",['locations'=>$locations]);
    }


    public function datagetir(){

        $id = intval($_GET["id"]);

        $data = map::where("id", "=",$id)->first()->data;
        return $data;

    }



    public function showMaps(Request $request)
    {

        for($i=20;$i <= 24; $i++){



            $url = 'https://api.afetharita.com/tweets/areas?format=json&ne_lat=100&ne_lng=0&page='.$i.'&page_size=200&sw_lat=100&sw_lng=0';
            $response = Http::get($url)->body();




            $data = json_decode($response);


            $locations = [];

            foreach ($data->results as $key => $value) {


                //$sorgu = map::where("id","=",$value->id)->count();




                    $datas = json_encode($value);
                    map::create(array("data" => $datas,"data_id" => $value->id));




                //$locations[] = array("lat" => $value->loc[0],"lng" => $value->loc[1],"label" => "adres");

            }

        }

        exit;




        return view("show-map",['locations'=>$locations]);
    }



    public function testmap(Request $request)
    {

        $url = 'https://api.afetharita.com/tweets/locations?format=json&page=1';
        $response = Http::get($url)->body();
        $data = json_decode($response);




        foreach ($data->results as $key => $value) {




            $initialMarkers[] = array(
                "position" => array("lat" => $value->loc[0],"lng" => $value->loc[1],
                "label" => array("color" => "red","test" => "DEneme adrees"),
                "draggable" => true,
            )
            );




        }




        return view("testmap",compact('initialMarkers'));
    }



}
