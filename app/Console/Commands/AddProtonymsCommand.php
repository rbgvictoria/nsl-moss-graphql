<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TaxonomicNameUsage;

class AddProtonymsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nsl:add-protonyms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates protonym_id field in instance table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tnus = TaxonomicNameUsage::all()->filter(function($tnu) {
            return !$tnu->instance_type->relationship 
                || $tnu->instance_type->name === 'taxonomic synonym';
        });

        $bar = $this->output->createProgressBar(count($tnus));

        $bar->start();

        foreach ($tnus as $tnu) {
            $protonym = $this->getProtonym($tnu);
            TaxonomicNameUsage::where('id', $tnu->id)->update(['protonym_id' => $protonym->id]);
            $bar->advance();
        }

        $bar->finish();
    }

    protected function getProtonym($tnu)
    {
        if ($tnu->instance_type->primary_instance) {
            if ($tnu->basionym) {
                return $tnu->basionym;
            }
        }
        else {
            if ($tnu->primaryNameUsage) {
                if ($tnu->primaryNameUsage->basionym) {
                    return $tnu->primaryNameUsage->basionym;
                }
                else {
                    return $tnu->primaryNameUsage;
                }
            }
        }
        return $tnu;
    }
}
