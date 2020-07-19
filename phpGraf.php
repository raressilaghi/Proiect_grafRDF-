<?php

require 'vendor/autoload.php';
     $client=new EasyRdf_Sparql_Client("http://localhost:8080/rdf4j-server/repositories/videogames");
     print "<b>Afisarea tuturor jocurilor video din graf</b>";
     $query1 = "
     PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
     PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
     PREFIX s: <http://silaghi.ro/myschema#>
     PREFIX schema: <http://schema.org/>
     SELECT ?x
     WHERE{
         ?y  a schema:VideoGame.
         ?y  rdfs:label ?x.
     }";
     $res = $client->query($query1);
     print "<table style='border: 1px solid black;'><tr style='border: 1px solid black;'><th style='border: 1px solid black;'>Jocuri Video</th></tr>";
     foreach ($res as $r)
     {
         print "<tr style='border: 1px solid black;'><td style='border: 1px solid black;'>".$r->x."</td></tr>";
     }
     print "</table>";
     print "<br/>";
 
 
     print "<b>Afisarea companiilor impreuna cu jocurile lansate si categoria din care fac acestea parte</b>";
     $client=new EasyRdf_Sparql_Client("http://localhost:8080/rdf4j-server/repositories/videogames");
     $query1 = "
     PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
     PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
     PREFIX s: <http://silaghi.ro/myschema#>
     PREFIX schema: <http://schema.org/>
     
     SELECT ?joc ?companie ?categorie
     WHERE{
         ?c s:hasCreated ?j.
  		 ?j rdfs:label ?joc.
    	 ?c rdfs:label ?companie.
  		 ?j s:hasCategory ?ct.
  		 ?ct rdfs:label ?categorie.
     }";
 $res = $client->query($query1);
 print "<table style='border: 1px solid black;'><tr style='border: 1px solid black;'><th style='border: 1px solid black;'>Companie</th><th style='border: 1px solid black;'>Joc Video</th><th style='border: 1px solid black;'>Categorie Joc</th></tr>";
 foreach ($res as $r)
 {
     print "<tr style='border: 1px solid black;'><td style='border: 1px solid black;'>".$r->companie."</td><td style='border: 1px solid black;'>".$r->joc."</td><td style='border: 1px solid black;'>".$r->categorie."</td></tr>";
 }
 print "</table>";

 
    if(isset($_POST['submit']))
    {
        $joc=htmlentities($_POST['den_joc'], ENT_QUOTES);
        $companie=htmlentities($_POST['comp_prod'], ENT_QUOTES);
        $categorie=htmlentities($_POST['game_ctg'], ENT_QUOTES);
        $tip=htmlentities($_POST['tip'], ENT_QUOTES);
        $eticheta=htmlentities($_POST['eticheta'], ENT_QUOTES);

        $client=new EasyRdf_Sparql_Client("http://localhost:8080/rdf4j-server/repositories/videogames");
        $query1 = "PREFIX  s: <http://silaghi.ro/myschema#> ASK{s:".$joc." ?x ?y}";
        $res = $client->query($query1);
        
        if( $res == "false" )
        {
            $adresa="http://localhost:8080/rdf4j-server/repositories/videogames/statements?update=";
            $interogare=urlencode("prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
            prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
            prefix s: <http://silaghi.ro/myschema#>
            prefix schema: <http://schema.org/> INSERT DATA{ GRAPH s:PS4Games{ s:".$companie." s:hasCreated s:".$joc.". s:".$joc." a schema:".$tip."; rdfs:label '".$eticheta."'; s:hasCategory s:".$categorie.". s:".$categorie." rdfs:label '".$categorie."'}}");
            $clienthttp=new EasyRdf_Http_Client($adresa.$interogare);
            print $clienthttp->request("POST");
           
        }
        else
        {
            print '<script>alert("Jocul este deja inserat in graf")</script>';
        }
        
    }

?>