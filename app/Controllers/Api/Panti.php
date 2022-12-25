<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Panti extends BaseController
{
    use ResponseTrait;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
    }

    public function get_list_panti()
    {
        $success = false;
        $message = 'Gagal Proses Data';
        $data_panti = [];

        // $pengelola_id = $this->request->getPost('pengelola_id ');
        $builderUsers = $this->db->table('panti');
        $builderUsers->where([])->select('*');
        $queryUsers    =  $builderUsers->get();

        if ($queryUsers->getNumRows() > 0) {
            $success = true;
            $message = 'get list panti success';
            $data_panti = $queryUsers->getResultArray();
        } else {
            $success = false;
            $message = 'get list panti failed';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_panti'] = $data_panti;

        return $this->response->setJSON($output);
    }

    public function insert_panti()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $nama = $this->request->getPost('nama');
        $description = $this->request->getPost('description');
        $image = $this->request->getPost('image');
        $logo = $this->request->getPost('logo');
        $alamat = $this->request->getPost('alamat');
        $dokumen_sertifikat = $this->request->getPost('dokumen_sertifikat');
        $pengelola_id = $this->request->getPost('pengelola_id');


        $dataValues['nama'] = $nama;
        $dataValues['description'] = $description;
        $dataValues['image'] = $image;
        $dataValues['logo'] = $logo;
        $dataValues['dokumen_sertifikat'] = $dokumen_sertifikat;
        $dataValues['alamat'] = $alamat;
        $tgl_buat = date('Y-m-d H:i:s');
        $dataValues['date_created'] = $tgl_buat;
        $dataValues['pengelola_id'] = $pengelola_id;

        $builderInserts = $this->db->table('panti');
        $insertDatas =  $builderInserts->insert($dataValues);

        if ($insertDatas) {
            $success = true;
            $message = 'Berhasil mendaftarkan panti';
        } else {
            $success = false;
            $message = 'Gagal Melakukan Registrasi, silahkan coba kembali';
        }


        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    public function update_panti()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
        $description = $this->request->getPost('description');
        $image = $this->request->getPost('image');
        $logo = $this->request->getPost('logo');
        $alamat = $this->request->getPost('alamat');
        $dokumen_sertifikat = $this->request->getPost('dokumen_sertifikat');
        $pengelola_id = $this->request->getPost('pengelola_id');


        $dataValues['id'] = $id;
        $dataValues['nama'] = $nama;
        $dataValues['description'] = $description;
        $dataValues['image'] = $image;
        $dataValues['logo'] = $logo;
        $dataValues['dokumen_sertifikat'] = $dokumen_sertifikat;
        $dataValues['alamat'] = $alamat;
        $tgl_buat = date('Y-m-d H:i:s');
        $dataValues['date_created'] = $tgl_buat;
        $dataValues['pengelola_id'] = $pengelola_id;


        $builderUpdate = $this->db->table('panti');
        $builderUpdate->where(['id' => $id]);
        $updateData =  $builderUpdate->update($dataValues);
        if ($updateData) {
            $success = true;
            $message = 'Berhasil ubah data panti';
        } else {
            $success = false;
            $message = 'Gagal ubah data panti, silahkan coba kembali';
        }

        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }
}
