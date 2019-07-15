<?php

namespace App\Tropicos;

use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;

class TropicosSearch 
{
    protected $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'http://services.tropicos.org/']);
    }

    public function search($name)
    {
        if (strpos($name->simple_name, '.') === false) {
            $searchString = str_replace(' ', '+', $name->simple_name);
            $type = 'exact';
        }
        else {
            $searchString = str_replace(' ', '+', $name->parent_name);
            $type = 'wildcard';
        }

        $res = $this->client->request('GET', "/Name/Search?name=$searchString&type=$type&apikey=ca3052db-4ce1-4290-9e88-04c02503451a&format=json");

        $matches = collect(json_decode($res->getBody()));
        $exactMatches = $matches->filter(function($match) use ($name) {
            return isset($match->ScientificName) && $match->ScientificName === $name->simple_name;
        });

        if ($exactMatches->count()) {
            foreach ($exactMatches as $match) {
                $insertArray = [
                    'name_id' => $name->id,
                    'tropicos_name_id' => $match->NameId,
                    'scientific_name' => $match->ScientificName,
                    'scientific_name_with_authors' => $match->ScientificNameWithAuthors,
                    'author' => isset($match->Author) ? $match->Author : null,
                    'family' => isset($match->Family) ? $match->Family : null,
                    'symbol' => isset($match->Symbol) ? $match->Symbol : null,
                    'rank_abbreviation' => isset($match->RankAbbreviation) ? $match->RankAbbreviation : null,
                    'nomenclatural_status_id' => isset($match->NomenclatureStatusId) ? $match->NomenclatureStatusId : null,
                    'nomenclatural_status_name' => isset($match->NomenclatureStatusName) ? $match->NomenclatureStatusName : null,
                    'display_reference' => isset($match->DisplayReference) ? $match->DisplayReference : null,
                    'display_date' => isset($match->DisplayDate) ? $match->DisplayDate : null,
                ];

                DB::table('tropicos.tropicos_name')->insert($insertArray);

            }
        }
    }


}