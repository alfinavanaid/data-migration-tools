<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lib\HandsomeAlfin\DataMigrationTools\DataMigrationTools;

class AnalyzeController
{

    public function index() : Response
    {
        $data_source = file_get_contents('http://127.0.0.1:8000/storage/data_source.json');
        $custom_relations_fields = file_get_contents('http://127.0.0.1:8000/storage/custom_relations_field.json');
        $DataMigrationTools = new DataMigrationTools();
        $DataMigrationTools->data_source = $data_source;
        $DataMigrationTools->custom_relations_fields = $custom_relations_fields;
        $DataMigrationTools->analyze();

        if (isset($_GET['table'])) {
            dd($DataMigrationTools->data_source_json[$_GET['table']]);
        }
        
        file_put_contents('storage/table_not_found.json', json_encode($DataMigrationTools->table_not_found), 1);
        file_put_contents('storage/data_source_object.json', json_encode($DataMigrationTools->data_source_json), 1);

        return new Response('oke', 200);
    }
}
