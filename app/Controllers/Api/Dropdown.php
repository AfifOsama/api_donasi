<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Dropdown extends BaseController
{
    use ResponseTrait;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
    }

    public function get_dropdown_jenis()
    {
        $success = false;
        $message = 'Gagal Proses Data';
        $data_dropdown = [];

        $builderDropdown = $this->db->table('jenis_donasi');
        $builderDropdown->select('id as value, nama_jenis as label');
        $queryDropdown    =  $builderDropdown->get();

        if ($queryDropdown->getNumRows() > 0) {
            $success = true;
            $message = 'get list dropdown success';
            $data_dropdown = $queryDropdown->getResultArray();
        } else {
            $success = false;
            $message = 'get list dropdown failed';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_dropdown'] = $data_dropdown;

        return $this->response->setJSON($output);
    }

    public function get_dropdown_satuan()
    {
        $success = false;
        $message = 'Gagal Proses Data';
        $data_dropdown = [];

        $builderDropdown = $this->db->table('satuan_donasi');
        $builderDropdown->select('id as value, nama_satuan as label');
        $queryDropdown    =  $builderDropdown->get();

        if ($queryDropdown->getNumRows() > 0) {
            $success = true;
            $message = 'get list dropdown success';
            $data_dropdown = $queryDropdown->getResultArray();
        } else {
            $success = false;
            $message = 'get list dropdown failed';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_dropdown'] = $data_dropdown;

        return $this->response->setJSON($output);
    }
}
