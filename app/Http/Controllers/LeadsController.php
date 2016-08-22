<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class LeadsController extends Controller
{
    protected $connector;

    public function __construct()
    {
        $this->connector = app('SugaConnector');
    }

    public function getAllLeads()
    {
        $excel = app('excel');

        $excel->create('Leads', function($excel) {

            // Set the title
            $excel->setTitle('Exported Leads');

            // Chain the setters
            $excel->setCreator('Petar')
                ->setCompany('Intelestream');

            // Call them separately
            $excel->setDescription('Demo export to Leads');

            $excel->sheet('Sheet1', function($sheet){
                $sheet->setOrientation('landscape');

                $sheet->fromArray($this->connector->getLeads(['items' => 21])['records']);
            });

        })->download('csv');

        /*dd($excel);*/
        /*return $message['Message'] = 'No errors, means it\'s working so far';*/
    }
}
