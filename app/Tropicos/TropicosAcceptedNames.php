<?php

namespace App\Tropicos;

use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;

class TropicosAcceptedNames extends Tropicos
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @param \int $id
     * @return void
     */
    public function getAcceptedNames($id) 
    {
        $apiKey = env('TROPICOS_API_KEY');
        $response = $this->client->request('GET', "/Name/$id/AcceptedNames?apikey=$apiKey&format=json");
        $data = json_decode($response->getBody());
        foreach ($data as $item) {
            if (!$this->findTropicosName($item->AcceptedName->NameId)) {
                $this->getNameDetails($item->AcceptedName->NameId);
            }
            if (!$this->findReference($item->Reference->ReferenceId)) {
                $this->insertReference($item->Reference);
            }
            $this->insertAcceptedName($id, $item);
        }
    }

    /**
     *
     * @param \int $id
     * @param \stdClass $data
     * @return void
     */
    protected function insertAcceptedName($id, $data)
    {
        $now = date('Y-m-d H:i:s');
        $insertArray = [
            'created_at' => $now,
            'updated_at' => $now,
            'tropicos_name_id' => $id,
            'tropicos_accepted_name_id' => $data->AcceptedName->NameId,
            'reference_id' => $data->Reference->ReferenceId,
        ];
        DB::table('tropicos.tropicos_accepted_names')->insert($insertArray);
    }
}