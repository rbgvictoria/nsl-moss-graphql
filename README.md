# NSL GraphQL Schema

Draft GraphpQL schema for the National Species Lists, implemented for the Moss shard 

- **Try it out:** https://nsl-mosses.rbg.vic.gov.au/graphql-playground

- GraphQL schema: https://github.com/rbgvictoria/nsl-mosses-graphql/blob/master/graphql/schema.graphql

- includes: https://github.com/rbgvictoria/nsl-mosses-graphql/tree/master/app/GraphQL/Types

- Printed full schema: https://github.com/rbgvictoria/nsl-mosses-graphql/blob/master/lighthouse-schema.graphql

- Models: https://github.com/rbgvictoria/nsl-mosses-graphql/tree/master/app/Models

- I had to add a protonym_id field to the instance table to make queries 
  including taxonomicNameUsages (for a protonym) and homotypicSynonyms work. 
  
  - migration that adds the field:
  https://github.com/rbgvictoria/nsl-mosses-graphql/blob/master/database/migrations/2019_07_05_035007_add_protonym_id_to_instance.php

  - command that populates the field: 
  https://github.com/rbgvictoria/nsl-mosses-graphql/blob/master/app/Console/Commands/AddProtonymsCommand.php