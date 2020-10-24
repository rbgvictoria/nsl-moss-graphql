
-- accepted names
SELECT 
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || i.id AS taxon_id,
  'https://id.biodiversity.org.au/name/ausmoss/'::text || n.id AS scientific_name_id,
  n.simple_name AS scientific_name,
  CASE
      WHEN n.author_id IS NOT NULL THEN "substring"(n.full_name::text, char_length(n.simple_name::text) + 2)
      ELSE NULL::text
  END AS scientific_name_authorship,
  'https://id.biodiversity.org.au/reference/ausmoss/'::text || npi.id AS name_published_in_id,
  npi.name_published_in,
  npi.name_published_in_year,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || at.according_to_id AS name_according_to_id,
  at.according_to AS name_according_to,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || onu.original_name_usage_id AS original_name_usage_id,
  onu.original_name_usage,
  NULL::text AS accepted_name_usage_id,
  NULL::text AS accepted_name_usage,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || pi.id AS parent_name_usage_id,
  (pn.simple_name::text || ' sec. '::text) || at.according_to AS parent_name_usage,
  CASE te.rank
      WHEN 'Regnum'::text THEN 'kingdom'::text
      WHEN 'Division'::text THEN 'phylum'::text
      WHEN 'Classis'::text THEN 'class'::text
      WHEN 'Subclassis'::text THEN 'subclass'::text
      WHEN 'Superordo'::text THEN 'superorder'::text
      WHEN 'Ordo'::text THEN 'order'::text
      WHEN 'Subordo'::text THEN 'suborder'::text
      WHEN 'Familia'::text THEN 'family'::text
      WHEN 'Subfamilia'::text THEN 'subfamily'::text
      WHEN 'Genus'::text THEN 'genus'::text
      WHEN 'Subgenus'::text THEN 'subgenus'::text
      WHEN 'Sectio'::text THEN 'section'::text
      WHEN 'Species'::text THEN 'species'::text
      WHEN 'Subspecies'::text THEN 'subspecies'::text
      WHEN 'Varietas'::text THEN 'variety'::text
      WHEN 'Forma'::text THEN 'form'::text
      ELSE NULL::text
  END AS taxon_rank,
  'accepted'::text AS taxonomic_status,
  CASE te.excluded
      WHEN true THEN 'excluded'::text
      ELSE 'present'::text
  END AS occurrence_status,
  replace(tve.name_path, '/'::text, '|'::text) AS higher_classification,
  tve.name_path

FROM tree_version_element tve
JOIN tree_element te ON tve.tree_element_id = te.id
JOIN instance i ON te.instance_id = i.id
JOIN name n ON i.name_id = n.id

LEFT JOIN ( -- name published in
  SELECT 
    i_1.name_id,
    r.id,
    CASE
        WHEN r.ref_type_id <> 17277 THEN (r.citation::text || ': '::text) || i_1.page::text
        ELSE (r.title::text || ': '::text) || i_1.page::text
    END AS name_published_in,
    CASE
        WHEN r.year IS NOT NULL THEN r.year::text
        ELSE "substring"(r.iso_publication_date::text, 1, 4)
    END AS name_published_in_year
  FROM instance i_1
  JOIN instance_type it ON i_1.instance_type_id = it.id
  LEFT JOIN reference r ON i_1.reference_id = r.id
  WHERE it.primary_instance = true
) npi ON n.id = npi.name_id

LEFT JOIN ( -- according to
  SELECT 
    r.id AS according_to_id,
    ((a.name::text || '('::text) || 
        CASE 
          WHEN r.year IS NOT NULL THEN r.year::text
          ELSE "substring"(r.iso_publication_date::text, 1, 4)
        END) || ')'::text AS according_to
  FROM reference r
  LEFT JOIN author a ON r.author_id = a.id
) at ON i.reference_id = at.according_to_id

LEFT JOIN ( -- original name usage
  SELECT 
    n_1.id AS name_id,
    COALESCE(bas.original_name_usage_id, pri.original_name_usage_id) AS original_name_usage_id,
    COALESCE(bas.original_name_usage, pri.original_name_usage) AS original_name_usage
  FROM name n_1
  LEFT JOIN ( -- basionym / replaced synonym
    SELECT 
      i_1.name_id,
      ci.id AS original_name_usage_id,
      (n_2.simple_name::text || ' sec. '::text) || at_1.according_to AS original_name_usage
    FROM instance i_1
    JOIN instance rel ON i_1.id = rel.cited_by_id AND (rel.instance_type_id = ANY (ARRAY[453821::bigint, 453823::bigint]))
    JOIN instance ci ON rel.cites_id = ci.id
    JOIN name n_2 ON ci.name_id = n_2.id
    JOIN ( -- according to
      SELECT 
        r.id AS according_to_id,
        ((a.name::text || ' ('::text) ||
            CASE
                WHEN r.year IS NOT NULL THEN r.year::text
                ELSE "substring"(r.iso_publication_date::text, 1, 4)
            END) || ')'::text AS according_to
      FROM reference r
      LEFT JOIN author a ON r.author_id = a.id
    ) at_1 ON ci.reference_id = at_1.according_to_id
  ) bas ON n_1.id = bas.name_id
  LEFT JOIN ( -- primary instance is used if name does not have a basionym or replaced synonym
    SELECT 
      i_1.name_id,
      i_1.id AS original_name_usage_id,
      (n_2.simple_name::text || ' sec. '::text) || at_1.according_to AS original_name_usage
    FROM instance i_1
    JOIN instance_type it ON i_1.instance_type_id = it.id
    JOIN name n_2 ON i_1.name_id = n_2.id
    JOIN ( -- according to
      SELECT 
        r.id AS according_to_id,
        ((a.name::text || ' ('::text) ||
            CASE
                WHEN r.year IS NOT NULL THEN r.year::text
                ELSE "substring"(r.iso_publication_date::text, 1, 4)
            END) || ')'::text AS according_to
        FROM reference r
        LEFT JOIN author a ON r.author_id = a.id
      ) at_1 ON i_1.reference_id = at_1.according_to_id
    WHERE it.primary_instance = true) pri ON n_1.id = pri.name_id
    GROUP BY n_1.id, bas.original_name_usage_id, bas.original_name_usage, pri.original_name_usage_id, pri.original_name_usage
) onu ON n.id = onu.name_id

-- parent name usage
LEFT JOIN tree_version_element ptve ON tve.parent_id = ptve.element_link
LEFT JOIN tree_element pte ON ptve.tree_element_id = pte.id
LEFT JOIN instance pi ON pte.instance_id = pi.id
LEFT JOIN name pn ON pi.name_id = pn.id

WHERE 
  -- current tree version
  tve.tree_version_id = ( 
    SELECT tree_version.id
    FROM tree_version
    WHERE tree_version.published = true 
      AND tree_version.published_at = ( 
        SELECT max(tree_version_1.published_at) AS max
        FROM tree_version tree_version_1
        WHERE tree_version_1.published = true
      )
  ) 
  
  -- exclude excluded names
  AND te.excluded = false


UNION

-- homotypic synonyms (same original name usage as accepted names)
SELECT 
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || npi.instance_id AS taxon_id,
  'https://id.biodiversity.org.au/name/ausmoss/'::text || alln.id AS scientific_name_id,
  alln.taxonomic_name_string AS scientific_name,
  alln.authorship AS scientific_name_authorship,
  'https://id.biodiversity.org.au/reference/ausmoss/'::text || npi.reference_id AS name_published_in_id,
  npi.name_published_in,
  npi.name_published_in_year,
  NULL::text AS name_according_to_id,
  NULL::text AS name_according_to,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || onu.original_name_usage_id AS original_name_usage_id,
  onu.original_name_usage,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || i.id AS accepted_name_usage_id,
  (n.simple_name::text || ' sec. '::text) || at.according_to AS accepted_name_usage,
  NULL::text AS parent_name_usage_id,
  NULL::text AS parent_name_usage,
  NULL::text AS taxon_rank,
  'homotypic synonym'::text AS taxonomic_status,
  NULL::text AS occurrence_status,
  NULL::text AS higher_classification,
  tve.name_path
   
FROM tree_version_element tve
JOIN tree_element te ON tve.tree_element_id = te.id
JOIN instance i ON te.instance_id = i.id
JOIN name n ON i.name_id = n.id

LEFT JOIN ( 
  SELECT 
    r.id AS according_to_id,
    a.name::text || '('::text ||
        CASE
            WHEN r.year IS NOT NULL THEN r.year::text
            ELSE "substring"(r.iso_publication_date::text, 1, 4)
        END || ')'::text AS according_to
  FROM reference r
  LEFT JOIN author a ON r.author_id = a.id
) at ON i.reference_id = at.according_to_id
     
LEFT JOIN ( 
  SELECT 
    n_1.id AS name_id,
    COALESCE(bas.original_name_usage_id, pri.original_name_usage_id) AS original_name_usage_id,
    COALESCE(bas.original_name_usage, pri.original_name_usage) AS original_name_usage
  FROM name n_1
  LEFT JOIN ( 
    SELECT 
      i_1.name_id,
      ci.id AS original_name_usage_id,
      n_2.simple_name::text || ' sec. '::text || at_1.according_to AS original_name_usage
    FROM instance i_1
      JOIN instance rel ON i_1.id = rel.cited_by_id AND (rel.instance_type_id = ANY (ARRAY[453821::bigint, 453823::bigint]))
      JOIN instance ci ON rel.cites_id = ci.id
      JOIN name n_2 ON ci.name_id = n_2.id
      JOIN ( 
        SELECT 
          r.id AS according_to_id,
          a.name::text || ' ('::text ||
              CASE
                  WHEN r.year IS NOT NULL THEN r.year::text
                  ELSE "substring"(r.iso_publication_date::text, 1, 4)
              END || ')'::text AS according_to
      FROM reference r
      LEFT JOIN author a ON r.author_id = a.id
    ) at_1 ON ci.reference_id = at_1.according_to_id
  ) bas ON n_1.id = bas.name_id
  LEFT JOIN ( 
    SELECT 
      i_1.name_id,
      i_1.id AS original_name_usage_id,
      n_2.simple_name::text || ' sec. '::text || at_1.according_to AS original_name_usage
    FROM instance i_1
    JOIN instance_type it ON i_1.instance_type_id = it.id
    JOIN name n_2 ON i_1.name_id = n_2.id
    JOIN ( 
      SELECT 
        r.id AS according_to_id,
        a.name::text || ' ('::text ||
            CASE
                WHEN r.year IS NOT NULL THEN r.year::text
                ELSE "substring"(r.iso_publication_date::text, 1, 4)
            END || ')'::text AS according_to
      FROM reference r
      LEFT JOIN author a ON r.author_id = a.id
    ) at_1 ON i_1.reference_id = at_1.according_to_id
    WHERE it.primary_instance = true
  ) pri ON n_1.id = pri.name_id
  GROUP BY n_1.id, bas.original_name_usage_id, bas.original_name_usage, pri.original_name_usage_id, pri.original_name_usage
) onu ON n.id = onu.name_id

JOIN all_names alln ON onu.original_name_usage_id = alln.protonym_instance_id AND n.id <> alln.id AND alln.nomenclatural_status = 'legitimate'::text

LEFT JOIN ( -- name published in
  SELECT 
    i_1.id AS instance_id,
    i_1.name_id,
    r.id AS reference_id,
    CASE
        WHEN r.ref_type_id <> 17277 THEN r.citation::text || ': '::text || i_1.page::text
        ELSE r.title::text || ': '::text || i_1.page::text
    END AS name_published_in,
    CASE
        WHEN r.year IS NOT NULL THEN r.year::text
        ELSE substring(r.iso_publication_date::text, 1, 4)
    END AS name_published_in_year
  FROM instance i_1
  JOIN instance_type it ON i_1.instance_type_id = it.id
  LEFT JOIN reference r ON i_1.reference_id = r.id
  WHERE it.primary_instance = true
) npi ON alln.id = npi.name_id
  

WHERE 
  -- current tree version
  tve.tree_version_id = ( 
    SELECT tree_version.id
    FROM tree_version
    WHERE tree_version.published = true AND tree_version.published_at = (
      SELECT max(tree_version_1.published_at) AS max
      FROM tree_version tree_version_1
      WHERE tree_version_1.published = true
    )
  ) 
  -- exclude exclude names
  AND te.excluded = false


UNION

-- heterotypic synonyms
SELECT 
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || si.id AS taxon_id,
  'https://id.biodiversity.org.au/name/ausmoss/'::text || si.name_id AS scientific_name_id,
  sn.simple_name AS scientific_name,
  CASE
    WHEN sn.author_id IS NOT NULL THEN "substring"(sn.full_name::text, char_length(sn.simple_name::text) + 2)
    ELSE NULL::text
  END AS scientific_name_authorship,
  'https://id.biodiversity.org.au/reference/ausmoss/'::text || npi.id AS name_published_in_id,
  npi.name_published_in,
  npi.name_published_in_year,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || at.according_to AS name_according_to_id,
  at.according_to AS name_according_to,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || onu.original_name_usage_id AS original_name_usage_id,
  onu.original_name_usage,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || i.id AS accepted_name_usage_id,
  n.simple_name::text || ' sec. '::text || at.according_to AS accepted_name_usage,
  NULL::text AS parent_name_usage_id,
  NULL::text AS parent_name_usage,
  NULL::text AS taxon_rank,
  'heterotypic synonym'::text AS taxonomic_status,
  NULL::text AS occurrence_status,
  NULL::text AS higher_classification,
  tve.name_path

FROM tree_version_element tve
JOIN tree_element te ON tve.tree_element_id = te.id
JOIN instance i ON te.instance_id = i.id
JOIN name n ON i.name_id = n.id
JOIN instance si ON i.id = si.cited_by_id
JOIN instance_type sit ON si.instance_type_id = sit.id
JOIN name sn ON si.name_id = sn.id

LEFT JOIN ( -- name published in
  SELECT 
    i_1.name_id,
    r.id,
    CASE
        WHEN r.ref_type_id <> 17277 THEN r.citation::text || ': '::text || i_1.page::text
        ELSE r.title::text || ': '::text || i_1.page::text
    END AS name_published_in,
    CASE
      WHEN r.year IS NOT NULL THEN r.year::text
      ELSE substring(r.iso_publication_date::text, 1, 4)
    END AS name_published_in_year
  FROM instance i_1
  JOIN instance_type it ON i_1.instance_type_id = it.id
  LEFT JOIN reference r ON i_1.reference_id = r.id
  WHERE it.primary_instance = true
) npi ON sn.id = npi.name_id

LEFT JOIN ( -- according to
  SELECT 
    r.id AS according_to_id,
    a.name::text || '('::text ||
        CASE
          WHEN r.year IS NOT NULL THEN r.year::text
          ELSE "substring"(r.iso_publication_date::text, 1, 4)
        END || ')'::text AS according_to
  FROM reference r
  LEFT JOIN author a ON r.author_id = a.id
) at ON si.reference_id = at.according_to_id

LEFT JOIN ( -- original name usage
  SELECT 
    n_1.id AS name_id,
    COALESCE(bas.original_name_usage_id, pri.original_name_usage_id) AS original_name_usage_id,
    COALESCE(bas.original_name_usage, pri.original_name_usage) AS original_name_usage
  FROM name n_1
  LEFT JOIN ( -- basionym or replaced synonym
    SELECT 
      i_1.name_id,
      ci.id AS original_name_usage_id,
      n_2.simple_name::text || ' sec. '::text || at_1.according_to AS original_name_usage
    FROM instance i_1
    JOIN instance rel ON i_1.id = rel.cited_by_id AND (rel.instance_type_id = ANY (ARRAY[453821::bigint, 453823::bigint]))
    JOIN instance ci ON rel.cites_id = ci.id
    JOIN name n_2 ON ci.name_id = n_2.id
    JOIN ( 
      SELECT 
        r.id AS according_to_id,
        a.name::text || ' ('::text ||
            CASE
                WHEN r.year IS NOT NULL THEN r.year::text
                ELSE "substring"(r.iso_publication_date::text, 1, 4)
            END || ')'::text AS according_to
      FROM reference r
      LEFT JOIN author a ON r.author_id = a.id
    ) at_1 ON ci.reference_id = at_1.according_to_id
  ) bas ON n_1.id = bas.name_id
  LEFT JOIN ( -- if the name does not have a basionym or replaced synonym the primary instance is used
    SELECT 
      i_1.name_id,
      i_1.id AS original_name_usage_id,
      n_2.simple_name::text || ' sec. '::text || at_1.according_to AS original_name_usage
    FROM 
      instance i_1
    JOIN instance_type it ON i_1.instance_type_id = it.id
    JOIN name n_2 ON i_1.name_id = n_2.id
    JOIN ( -- according to
      SELECT 
        r.id AS according_to_id,
        a.name::text || ' ('::text ||
            CASE
              WHEN r.year IS NOT NULL THEN r.year::text
              ELSE "substring"(r.iso_publication_date::text, 1, 4)
            END || ')'::text AS according_to
      FROM reference r
      LEFT JOIN author a ON r.author_id = a.id
    ) at_1 ON i_1.reference_id = at_1.according_to_id
    WHERE it.primary_instance = true
  ) pri ON n_1.id = pri.name_id
  GROUP BY n_1.id, bas.original_name_usage_id, bas.original_name_usage, pri.original_name_usage_id, pri.original_name_usage
) onu ON sn.id = onu.name_id

-- include only "protonyms" and only legitimate names
JOIN all_names an ON sn.id = an.id AND sn.id = an.protonym AND an.nomenclatural_status = 'legitimate'::text

WHERE 
  -- current tree version
  tve.tree_version_id = (
    SELECT tree_version.id
    FROM tree_version
    WHERE tree_version.published = true AND tree_version.published_at = ( 
      SELECT max(tree_version_1.published_at) AS max
      FROM tree_version tree_version_1
      WHERE tree_version_1.published = true
    )
  ) AND sit.name::text = 'taxonomic synonym'::text


UNION

-- other synonyms (same original name usage as heterotypic synonyms)
SELECT 
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || npi.instance_id AS taxon_id,
  'https://id.biodiversity.org.au/name/ausmoss/'::text || alln.id AS scientific_name_id,
  alln.taxonomic_name_string AS scientific_name,
  alln.authorship AS scientific_name_authorship,
  'https://id.biodiversity.org.au/reference/ausmoss/'::text || npi.reference_id AS name_published_in_id,
  npi.name_published_in,
  npi.name_published_in_year,
  NULL::text AS name_according_to_id,
  NULL::text AS name_according_to,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || onu.original_name_usage_id AS original_name_usage_id,
  onu.original_name_usage,
  'https://id.biodiversity.org.au/instance/ausmoss/'::text || i.id AS accepted_name_usage_id,
  (n.simple_name::text || ' sec. '::text) || at.according_to AS accepted_name_usage,
  NULL::text AS parent_name_usage_id,
  NULL::text AS parent_name_usage,
  NULL::text AS taxon_rank,
  'synonym'::text AS taxonomic_status,
  NULL::text AS occurrence_status,
  NULL::text AS higher_classification,
  tve.name_path

FROM tree_version_element tve
JOIN tree_element te ON tve.tree_element_id = te.id
JOIN instance i ON te.instance_id = i.id
JOIN name n ON i.name_id = n.id
JOIN instance si ON i.id = si.cited_by_id
JOIN instance_type sit ON si.instance_type_id = sit.id
JOIN name sn ON si.name_id = sn.id

LEFT JOIN ( -- according to (only used for accepted name usage)
  SELECT 
    r.id AS according_to_id,
    a.name::text || '('::text ||
        CASE
            WHEN r.year IS NOT NULL THEN r.year::text
            ELSE "substring"(r.iso_publication_date::text, 1, 4)
        END || ')'::text AS according_to
  FROM reference r
  LEFT JOIN author a ON r.author_id = a.id
) at ON si.reference_id = at.according_to_id

LEFT JOIN ( -- original name usage
  SELECT 
    n_1.id AS name_id,
    COALESCE(bas.original_name_usage_id, pri.original_name_usage_id) AS original_name_usage_id,
    COALESCE(bas.original_name_usage, pri.original_name_usage) AS original_name_usage
  FROM name n_1
  LEFT JOIN ( -- basionym or replaced synonym
    SELECT 
      i_1.name_id,
      ci.id AS original_name_usage_id,
      n_2.simple_name::text || ' sec. '::text || at_1.according_to AS original_name_usage
    FROM instance i_1
    JOIN instance rel ON i_1.id = rel.cited_by_id AND (rel.instance_type_id = ANY (ARRAY[453821::bigint, 453823::bigint]))
    JOIN instance ci ON rel.cites_id = ci.id
    JOIN name n_2 ON ci.name_id = n_2.id
    JOIN ( 
      SELECT 
        r.id AS according_to_id,
        a.name::text || ' ('::text ||
            CASE
                WHEN r.year IS NOT NULL THEN r.year::text
                ELSE "substring"(r.iso_publication_date::text, 1, 4)
            END || ')'::text AS according_to
      FROM reference r
      LEFT JOIN author a ON r.author_id = a.id
    ) at_1 ON ci.reference_id = at_1.according_to_id
  ) bas ON n_1.id = bas.name_id
  LEFT JOIN ( -- if name does not have a basionym or replaced synonym, the primary instance is used
    SELECT 
      i_1.name_id,
      i_1.id AS original_name_usage_id,
      n_2.simple_name::text || ' sec. '::text || at_1.according_to AS original_name_usage
    FROM instance i_1
    JOIN instance_type it ON i_1.instance_type_id = it.id
    JOIN name n_2 ON i_1.name_id = n_2.id
    JOIN ( -- according to
      SELECT r.id AS according_to_id,
        a.name::text || ' ('::text ||
            CASE
              WHEN r.year IS NOT NULL THEN r.year::text
              ELSE "substring"(r.iso_publication_date::text, 1, 4)
            END || ')'::text AS according_to
      FROM reference r
      LEFT JOIN author a ON r.author_id = a.id
    ) at_1 ON i_1.reference_id = at_1.according_to_id
    WHERE it.primary_instance = true
  ) pri ON n_1.id = pri.name_id
  GROUP BY n_1.id, bas.original_name_usage_id, bas.original_name_usage, pri.original_name_usage_id, pri.original_name_usage
) onu ON sn.id = onu.name_id

-- only include "protonyms" and only include legitimate names
JOIN all_names an ON sn.id = an.id AND sn.id = an.protonym AND an.nomenclatural_status = 'legitimate'::text
-- other legitimate names with the same original name usage
JOIN all_names alln ON onu.original_name_usage_id = alln.protonym_instance_id AND n.id <> alln.id AND alln.nomenclatural_status = 'legitimate'::text

LEFT JOIN ( -- name published in
  SELECT 
    i_1.id AS instance_id,
    i_1.name_id,
    r.id AS reference_id,
    CASE
      WHEN r.ref_type_id <> 17277 THEN (r.citation::text || ': '::text) || i_1.page::text
      ELSE (r.title::text || ': '::text) || i_1.page::text
    END AS name_published_in,
    CASE
      WHEN r.year IS NOT NULL THEN r.year::text
      ELSE "substring"(r.iso_publication_date::text, 1, 4)
    END AS name_published_in_year
  FROM instance i_1
  JOIN instance_type it ON i_1.instance_type_id = it.id
  LEFT JOIN reference r ON i_1.reference_id = r.id
  WHERE it.primary_instance = true
) npi ON alln.id = npi.name_id

WHERE 
  tve.tree_version_id = (
    SELECT tree_version.id
    FROM tree_version
    WHERE tree_version.published = true AND tree_version.published_at = (
      SELECT max(tree_version_1.published_at) AS max
      FROM tree_version tree_version_1
      WHERE tree_version_1.published = true
    )
  ) AND sit.name::text = 'taxonomic synonym'::text

  ORDER BY name_path
