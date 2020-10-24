<?php

namespace App\Tropicos;

use Illuminate\Support\Facades\DB;

class Tropicos
{
    protected $data;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['base_uri' => 'http://services.tropicos.org/']);
    }

    /**
     *
     * @param \int $nameId
     * @return void
     */
    protected function findTropicosName($nameId)
    {
        return DB::table('tropicos.tropicos_names')->where('id', $nameId)->value('id');
    }

    /**
     *
     * @param \int $nameId
     * @return void
     */
    protected function getNameDetails($nameId)
    {
        $apiKey = env('TROPICOS_API_KEY');
        $response = $this->client->request('GET', "/Name/$nameId?apikey=$apiKey&format=json");
        $nameDetail = json_decode($response->getBody());
        $now = date('Y-m-d H:i:s');
        $insertArray = [
            'id' => $nameId,
            'created_at' => $now,
            'updated_at' => $now,
            'scientific_name' => $nameDetail->ScientificName,
            'scientific_name_with_authors' => $nameDetail->ScientificNameWithAuthors,
            'family' => isset($nameDetail->Family) ? $nameDetail->Family : null,
            'symbol' => isset($nameDetail->Symbol) ? $nameDetail->Symbol : null,
            'rank_abbreviation' => isset($nameDetail->RankAbbreviation) ? $nameDetail->RankAbbreviation : null,
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
            'nomenclatural_status_id' => isset($nameDetail->NomenclatureStatusId) ? $nameDetail->NomenclatureStatusId : null,
            'nomenclatural_status_name' => isset($nameDetail->NomenclatureStatusName) ? $nameDetail->NomenclatureStatusName : null,
            'display_reference' => isset($nameDetail->DisplayReference) ? $nameDetail->DisplayReference : null,
            'display_date' => isset($nameDetail->DisplayDate) ? $nameDetail->DisplayDate : null,
            'name_published_citation' => isset($nameDetail->NamePublishedCitation) ? $nameDetail->NamePublishedCitation : null, 
            'type_specimens' => isset($nameDetail->TypeSpecimens) && $nameDetail->TypeSpecimens ? true : null, 
        ];
        DB::table('tropicos.tropicos_names')->insert($insertArray);
    }

    /**
     *
     * @param \int $refId
     * @return \int|null
     */
    protected function findReference($refId)
    {
        return DB::table('tropicos.tropicos_references')
                ->where('id', $refId)
                ->value('id');
    }

    /**
     * Undocumented function
     *
     * @param \stdClass $ref
     * @return void
     */
    protected function insertReference($ref)
    {
        $now = date('Y-m-d H:i:s');
        $insertArray = [
            'id' => $ref->ReferenceId,
            'created_at' => $now,
            'updated_at' => $now,
            'publication_id' => isset($ref->PublicationId) 
                    ? $ref->PublicationId : null,
            'author_string' => isset($ref->AuthorString) ? $ref->AuthorString : null,
            'article_title' => isset($ref->ArticleTitle) ? $ref->ArticleTitle : null,
            'collation' => isset($ref->Collation) ? $ref->Collation : null,
            'abbreviated_title' => isset($ref->AbbreviatedTitle) ? $ref->AbbreviatedTitle : null,
            'title_page_year' => isset($ref->TitlePageYear) ? $ref->TitlePageYear : null,
            'full_citation' => isset($ref->FullCitation) ? $ref->FullCitation : null,
        ];
        DB::table('tropicos.tropicos_references')->insert($insertArray);
    }
}
