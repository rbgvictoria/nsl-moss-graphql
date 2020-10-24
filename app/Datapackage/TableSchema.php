<?php
/**
 * Copyright 2019 Royal Botanic Gardens Victoria
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Datapackage;

use frictionlessdata\tableschema\Schema;
use frictionlessdata\tableschema\Table;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

/**
 * Undocumented class
 */
class TableSchema 
{
    public static function createTaxonomicNameUsageSchema()
    {
        return new Schema([
            'fields' => [
                [
                    'name' => 'id',
                    'title' => 'id',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [
                    'name' => 'http://rs.tdwg.org/tnu/terms/taxonomicName',
                    'title' => 'taxonomicName',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [
                    'name' => 'http://rs.tdwg.org/tnu/terms/accordingTo',
                    'title' => 'accordingTo',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [
                    'name' => 'http://rs.tdwg.org/tnu/terms/taxonomicStatus',
                    'title' => 'taxonomicStatus',
                    'type' => 'string',
                    'constraints' => [
                        'enum' => [
                            'accepted',
                            'synonym'
                        ]
                    ]
                ],
                [
                    'name' => 'http://rs.tdwg.org/tnu/terms/acceptedNameUsage',
                    'title' => 'acceptedNameUsage',
                    'type' => 'string',
                    'format' => 'uri'
                ],
                [
                    'name' => 'http://rs.tdwg.org/tnu/terms/parentNameUsage',
                    'title' => 'parentNameUsage',
                    'type' => 'string',
                    'format' => 'uri'
                ],
                [
                    'name' => 'http://rs.tdwg.org/dwc/terms/occurrenceStatus',
                    'title' => 'dwc:occurrenceStatus',
                    'type' => 'string'
                ]
            ],
            'primaryKey' => 'id'
        ]);
    }

    public static function createTaxonomicNamesSchema()
    {
        return new Schema([
            'fields' => [
                [// 0
                    'name' => 'id',
                    'title' => 'id',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [// 1
                    'name' => 'http://rs.tdwg.or/tnu/terms/taxonomicNameString',
                    'title' => 'taxonomicNameString',
                    'type' => 'string',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [// 2
                    'name' => 'http://rs.tdwg.or/tnu/terms/authorship',
                    'title' => 'authorship',
                    'type' => 'string'
                ],
                [// 3
                    'name' => 'http://rs.tdwg.or/tnu/terms/taxonomicNameStringWithAuthorship',
                    'title' => 'taxonomicNameStringWithAuthorship',
                    'type' => 'string'
                ],
                [// 4
                    'name' => 'http://rs.tdwg.or/tnu/terms/namePublishedIn',
                    'title' => 'namePublishedIn',
                    'type' => 'string',
                    'format' => 'uri'
                ],
                [// 5
                    'name' => 'http://rs.tdwg.or/tnu/terms/microReference',
                    'title' => 'microReference',
                    'type' => 'string'
                ],
                [// 6
                    'name' => 'http://rs.tdwg.or/tnu/terms/publicationYear',
                    'title' => 'publicationYear',
                    'type' => 'string'
                ],
                [// 7
                    'name' => 'http://rs.tdwg.or/tnu/terms/rank',
                    'title' => 'rank',
                    'type' => 'string',
                    'constraints' => [
                        'enum' => [
                            'kingdom',
                            'phylum',
                            'class',
                            'subclass',
                            'superorder',
                            'order',
                            'suborder',
                            'family',
                            'subfamily',
                            'genus',
                            'subgenus',
                            'section',
                            'species',
                            'variety',
                            'form'
                        ]
                    ]
                ],
                [// 8
                    'name' => 'http://rs.tdwg.or/tnu/terms/nomenclaturalStatus',
                    'title' => 'nomenclaturalStatus',
                    'type' => 'string',
                    'constraints' => [
                        'enum' => [
                            'legitimate',
                            'illegitimate',
                            'superfluous',
                            'conserved',
                            'rejected',
                            'sanctioned',
                            'invalid'
                        ]
                    ]
                ],
                [// 9
                    'name' => 'http://rs.tdwg.or/tnu/terms/basionym',
                    'title' => 'basionym',
                    'type' => 'string',
                    'format' => 'uri'
                ],
                [// 10
                    'name' => 'http://rs.tdwg.or/tnu/terms/replacedName',
                    'title' => 'replacedName',
                    'type' => 'string',
                    'format' => 'uri'
                ],
            ],
            'primaryKey' => 'id'
        ]);
    }

    public static function createBibliographicResourcesSchema()
    {
        return new Schema([
            'fields' => [
                [
                    'name' => 'id',
                    'title' => 'id',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [
                    'name' => 'resourceType',
                    'title' => 'resourceType',
                    'type' => 'string'
                ],
                [
                    'name' => 'creator',
                    'title' => 'creator',
                    'type' => 'string'
                ],
                [
                    'name' => 'publictionYear',
                    'title' => 'publicationYear',
                    'type' => 'string'
                ],
                [
                    'name' => 'title',
                    'title' => 'title',
                    'type' => 'string'
                ],
                [
                    'name' => 'parent',
                    'title' => 'parent',
                    'type' => 'string',
                    'format' => 'uri'
                ],
                [
                    'name' => 'volume',
                    'type' => 'string'
                ],
                [
                    'name' => 'pages',
                    'type' => 'string'
                ],
                [
                    'name' => 'publisher',
                    'type' => 'string'
                ],
            ],
            'primaryKey' => 'id'
        ]);
    }

    public static function createTaxonRelationshipAssertionsSchema()
    {
        return new Schema([
            'fields' => [
                [
                    'name' => 'id',
                    'title' => 'id',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [
                    'name' => 'http://rs.tdwg.or/tnu/terms/subjectTaxonomicNameUsage',
                    'title' => 'subjectTaxonomicNameUsage',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [
                    'name' => 'http://rs.tdwg.or/tnu/terms/relationshipType',
                    'title' => 'relationshipType',
                    'type' => 'string',
                    'constraints' => [
                        'required' => true,
                        'enum' => [
                            'isCongruentWith',
                            'includes',
                            'isIncludedIn',
                            'overlaps',
                            'excludes'
                        ]
                    ]
                ],
                [
                    'name' => 'http://rs.tdwg.or/tnu/terms/objectTaxonomicNameUsage',
                    'title' => 'objectTaxonomicNameUsage',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
                [
                    'name' => 'http://rs.tdwg.or/tnu/terms/accordingTo',
                    'title' => 'accordingTo',
                    'type' => 'string',
                    'format' => 'uri',
                    'constraints' => [
                        'required' => true
                    ]
                ],
            ],
            'primaryKey' => 'id'
        ]);
    }

    public static function zipIt($filename)
    {
        // Get real path for our folder
        $rootPath = realpath(storage_path('App') . '/tnu-datapackage');

        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }
}

