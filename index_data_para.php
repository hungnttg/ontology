<?php
require_once( "sparqllib.php" );
//	./fuseki-server --update --mem /ds
//	pizza/////
//$db = sparql_connect( "http://rdf.ecs.soton.ac.uk/sparql/" );
$db = sparql_connect( "http://localhost:3030/D3" ); //d3 phan biet chu hoa chu thuong
if( !$db ) { print sparql_errno() . ": " . sparql_error(). "---->loi o dong 6  \n"; exit; }
//sparql_ns( "foaf","http://xmlns.com/foaf/0.1/" );

//$sparql = "SELECT * WHERE { ?person a foaf:Person . ?person foaf:name ?name } LIMIT 5";
sparql_ns( "rdfs","http://www.w3.org/2000/01/rdf-schema#" );
sparql_ns( "owl","http://www.w3.org/2002/07/owl#" );

//$para = 'UnclosedPizza';
$para = '';
if (isset($_GET['name'])) {
  $para = $_GET['name'];
}
//test value: Michelangelo	Tiziano_Vecelli  Aristide_Maillol  Pieter_Bruegel_il_Vecchio 
//test value: Henri_Matisse  Osho

$sparql = "select distinct (?cha as ?album) (?con as ?artist) (?chau as ?artist_name) (str(?localName) as ?para_name) where {
  {
    SELECT  ?cha ?con ?chau (strafter(str(?chau), '#') AS ?localName) WHERE 
    {
      ?con rdfs:subClassOf ?cha .
      ?chau rdfs:subClassOf ?con .
    }
  }
      filter (?localName='".$para."')
}
LIMIT 25";
$result = sparql_query( $sparql ); 
if( !$result ) { print sparql_errno() . ": " . sparql_error(). "----> loi o dong 11 \n"; exit; }

$fields = sparql_field_array( $result );

print "<p>Number of rows: ".sparql_num_rows( $result )." results.</p>";
print "<table class='example_table'>";
print "<tr>";
foreach( $fields as $field )
{
	print "<th>$field</th>";
}
print "</tr>";
while( $row = sparql_fetch_array( $result ) )
{
	print "<tr>";
	foreach( $fields as $field )
	{
		print "<td>$row[$field]</td>";
	}
	print "</tr>";
}
print "</table>";
?>
