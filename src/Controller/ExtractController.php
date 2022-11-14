<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lib\Avanaone\DataMigrationTools\DataMigrationTools;

class ExtractController
{

    public function index() : Response
    {
        $data_source_json = file_get_contents('http://127.0.0.1:8000/storage/data_source_object.json');
        $DataMigrationTools = new DataMigrationTools();
        $DataMigrationTools->data_source_json = $data_source_json;
        $DataMigrationTools->extract();
        // $DataMigrationTools->analyze();
        
        // file_put_contents('storage/table_not_found.json', json_encode($DataMigrationTools->table_not_found), 1);
        // file_put_contents('storage/data_source_object.json', json_encode($DataMigrationTools->data_source_json), 1);

        return new Response('oke', 200);
    }
}
