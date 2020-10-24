<?php

namespace App\Tropicos;

use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;

class TropicosSearch extends Tropicos
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @param \stdClass $name
     * @return void
     */
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

        $response = $this->client->request('GET', "/Name/Search?name=$searchString&type=$type&apikey=ca3052db-4ce1-4290-9e88-04c02503451a&format=json");

        $matches = collect(json_decode($response->getBody()));
        $exactMatches = $matches->filter(function($match) use ($name) {
            return isset($match->ScientificName) && $match->ScientificName === $name->simple_name;
        });

        if ($exactMatches->count()) {
            foreach ($exactMatches as $match) {
                if (!$this->findTropicosName($match->NameId)) {
                    $this->getNameDetails($match->NameId);
                }
                $this->insertAusMossNameTropicosName($name->id, $match);
            }
        }
    }


    protected function insertAusMossNameTropicosName($ausMossNameId, $data)
    {
        $now = date('Y-m-d H:i:s');
        $insertArray = [
            'created_at' => $now,
            'updated_at' => $now,
            'ausmoss_name_id' => $ausMossNameId,
            'tropicos_name_id' => $data->NameId,
        ];

        DB::table('tropicos.ausmoss_names_tropicos_names')->insert($insertArray);
    }
}