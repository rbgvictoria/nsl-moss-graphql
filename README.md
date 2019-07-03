# NSL GraphQL Schema

Draft GraphpQL schema for the National Species Lists, implemented for the Moss shard 

- GraphQL schema: https://github.com/rbgvictoria/nsl-mosses-graphql/blob/master/graphql/schema.graphql

- includes: https://github.com/rbgvictoria/nsl-mosses-graphql/tree/master/app/GraphQL/Types

- Printed full schema: https://github.com/rbgvictoria/nsl-mosses-graphql/blob/master/lighthouse-schema.graphql

- Models: https://github.com/rbgvictoria/nsl-mosses-graphql/tree/master/app/Models

- I had to add a protonym_id field to the instance table to make queries 
  including taxonomicNameUsages (for a protonym) and homotypicSynonyms work. The 
  command that populates this field is in 
  https://github.com/rbgvictoria/nsl-mosses-graphql/blob/master/app/Console/Commands/AddProtonymsCommand.php