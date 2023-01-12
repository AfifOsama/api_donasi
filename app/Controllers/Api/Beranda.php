<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Beranda extends BaseController
{
    use ResponseTrait;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
    }

    public function get_list_photo_carousel()
    {
        $success = false;
        $message = 'Gagal Proses Data';
        $data_panti = [];

        $builderImpact = $this->db->table('impact');
        $builderImpact->select('impact.image');

        $queryImpact    =  $builderImpact->get();
        if ($queryImpact->getNumRows() > 0) {

            $success = true;
            $message = 'get list panti success';
            $data_panti = $queryImpact->getResultArray();
        } else {
            $success = false;
            $message = 'get list panti failed';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_panti'] = $data_panti;

        return $this->response->setJSON($output);
    }
}
