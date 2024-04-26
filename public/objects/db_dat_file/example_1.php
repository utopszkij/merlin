<?php
include_once (__DIR__.'/query.php');

    $query = new Query('test_query');
	$query->drop();
	$query->insert(Doc(["name" => "Teszt Elek", "phone" => "+36302106501"]));
	$query->insert(Doc(["name" => "Gipsz Jakab", "phone" => "+36301234567"]));
	$query->insert(Doc(["name" => "Noname", "phone" => "none"]));
	$query->insert(Doc(["name" => "Joska"]));
	$query->insert(Doc(["name" => "Kis Pista"]));
	$query->insert(Doc(["name" => "Peter"]));
	$query->insert(Doc(["name" => "Miska"]));
	$query->insert(Doc(["name" => "Tibi"]));
    $id = $query->insert(Doc(["name" => "Ilona"]));    

    echo 'Read by id '."\n";
	$rec = $query->where('id','=', $id)->all($id);
	echo JSON_encode($rec)."\n";

    echo 'Updateby id '."\n";
	$rec = $query->where('id','=', $id)->update(Doc(["name" => "Ilonka"]));
	$rec = $query->where('id','=',$id)->all($id);
	echo JSON_encode($rec)."\n";

    echo 'Query'."\n";
    $query = new Query('test_query');
    $recs = $query->select(['id','name'])
		->where('name','<>','')
		->where('id','>','')
		->orWhere('phone','=','66')
		->orderBy(['name','id'])
		->offset(0)
		->limit(3)
		->all();
    echo JSON_encode($recs)."\n";
?>
