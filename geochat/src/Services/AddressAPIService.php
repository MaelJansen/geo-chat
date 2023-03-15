<?php
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class AddressAPIService
 {
   public const base_uri = 'https://api-adresse.data.gouv.fr/';

   public function getlngLat(string $address): ?array
   {
    $client = new Client();
    $request = new Request('GET', 'https://api-adresse.data.gouv.fr/search/?q='.$address.'&postcode=');
    
    $res = $client->sendAsync($request)->wait();
    $data = json_decode($res->getBody());
    $lnglat = $data->features[0]->geometry->coordinates;
    if($lnglat != null){
      return $lnglat;
    }
    return null;
   }
 }

?>