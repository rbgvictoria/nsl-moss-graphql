<?php

namespace App\Tropicos;

use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;

class TropicosNameDetail 
{
  protected $client;

  public function __construct()
  {
      $this->client = new \GuzzleHttp\Client(['base_uri' => 'http://services.tropicos.org/']);
  }

  public function getNameDetail($id) 
  {
    $apiKey = env('TROPICOS_API_KEY');
    $response = $this->client->request('GET', "/Name/$id?apikey=$apiKey&format=json");

    $nameDetail = json_decode($response->getBody());

    $now = date('Y-m-d H:i:s');

    $updateArray = [
      'updated_at' => $now,
      'accepted_name_count' => isset($nameDetail->AcceptedNameCount) ? $nameDetail->AcceptedNameCount : 0, 
      'synonym_count' => isset($nameDetail->SynonymCount) ? $nameDetail->SynonymCount : 0, 
      'rank' => isset($nameDetail->Rank) ? $nameDetail->Rank : null, 
      'genus' => isset($nameDetail->Genus) ? $nameDetail->Genus : null, 
      'specific_epithet' => isset($nameDetail->SpeciesEpithet) ? $nameDetail->SpeciesEpithet : null, 
      'infraspecific_epithet' => isset($nameDetail->OtherEpithet) ? $nameDetail->OtherEpithet : null, 
      'source' => isset($nameDetail->Source) ? $nameDetail->Source : null, 
      'citation' => isset($nameDetail->Citation) ? $nameDetail->Citation : null, 
      'copyright' => isset($nameDetail->Copyright) ? $nameDetail->Copyright : null, 
      'author' => isset($nameDetail->Author) ? $nameDetail->Author : null, 
      'basionym_author' => isset($nameDetail->BasionymAuthor) ? $nameDetail->BasionymAuthor : null, 
      'name_published_citation' => isset($nameDetail->NamePublishedCitation) ? $nameDetail->NamePublishedCitation : null, 
      'type_specimens' => isset($nameDetail->TypeSpecimens) && $nameDetail->TypeSpecimens ? true : null, 
    ];

    DB::table('tropicos.tropicos_names')->where('tropicos_name_id', $id)->update($updateArray);

  }
}