<?php

namespace App\Tropicos;

use Illuminate\Support\Facades\DB;
use function GuzzleHttp\json_decode;

class TropicosNameReferences
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @param \int $nameId
     * @return void
     */
    public function getNameReferences($nameId)
    {
        $apiKey = env('TROPICOS_API_KEY');
        $response = $this->client->request('GET', "/Name/$nameId/References?apikey=$apiKey&format=json");
        $data = json_decode($response->getBody());
        foreach ($data as $item) {
            if (isset($item->Reference)) {
                if (!$this->findReference($item->Reference->ReferenceId)) {
                    $this->insertReference($item->Reference);
                }
                $this->insertNameReference($nameId, $item);
            }
        }

    }

    /**
     * Undocumented function
     *
     * @param \int $nameId
     * @param \stdClass $data
     * @return void
     */
    protected function insertNameReference($nameId, $data)
    {
        $now = date('Y-m-d H:i:s');
        $insertArray = [
            'created_at' => $now,
            'updated_at' => $now,
            'tropicos_name_id' => $nameId,
            'tropicos_reference_id' => $data->Reference->ReferenceId,
            'accepted_by' => isset($data->AcceptedBy) ? $data->AcceptedBy : null,
            'published_in' => isset($data->PublishedIn) ? $data->PublishedIn : null,
            'annotation' => isset($data->Annotation) ? $data->Annotation : null,
        ];
        DB::table('tropicos.tropicos_names_references')->insert($insertArray);
    }

}