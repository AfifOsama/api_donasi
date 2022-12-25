<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Impact extends BaseController
{
    use ResponseTrait;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
    }

    public function get_list_impact()
    {
        $success = false;
        $message = 'Gagal Proses Data';
        $data_impact = [];

        $panti_id = $this->request->getPost('panti_id');

        $builderImpact = $this->db->table('impact');
        $builderImpact->where(['impact.panti_id' => $panti_id])->select('impact.*, panti.nama as nama_panti, panti.id as id_panti, donasi.id as id_donasi, donasi.nama_donasi');
        $builderImpact->join('panti', 'panti.id = impact.panti_id', 'left');
        $builderImpact->join('donasi', 'donasi.id = impact.donasi_id', 'left');
        $queryImpact    =  $builderImpact->get();

        if ($queryImpact->getNumRows() > 0) {
            $success = true;
            $message = 'get list impact success';
            $data_impact = $queryImpact->getResultArray();
        } else {
            $success = false;
            $message = 'get list impact failed';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_impact'] = $data_impact;

        return $this->response->setJSON($output);
    }

    public function insert_impact()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $image = $this->request->getPost('image');
        $panti_id = $this->request->getPost('panti_id');
        $deskripsi = $this->request->getPost('deskripsi');
        $donasi_id = $this->request->getPost('donasi_id');


        $dataValues['image'] = $image;
        $dataValues['panti_id'] = $panti_id;
        $dataValues['deskripsi'] = $deskripsi;
        $dataValues['donasi_id'] = $donasi_id;
        $tgl_buat = date('Y-m-d H:i:s');
        $dataValues['date_created'] = $tgl_buat;

        $builderInserts = $this->db->table('impact');
        $insertDatas =  $builderInserts->insert($dataValues);

        if ($insertDatas) {
            $success = true;
            $message = 'Berhasil menambahkan konten dampak';
        } else {
            $success = false;
            $message = 'Gagal menambahkan konten dampak, silahkan coba kembali';
        }


        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    public function update_impact()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $id = $this->request->getPost('id');
        $image = $this->request->getPost('image');
        $panti_id = $this->request->getPost('panti_id');
        $deskripsi = $this->request->getPost('deskripsi');
        $donasi_id = $this->request->getPost('donasi_id');


        $dataValues['id'] = $id;
        $dataValues['image'] = $image;
        $dataValues['panti_id'] = $panti_id;
        $dataValues['deskripsi'] = $deskripsi;
        $dataValues['donasi_id'] = $donasi_id;
        $tgl_buat = date('Y-m-d H:i:s');
        $dataValues['date_created'] = $tgl_buat;

        $builderImpact = $this->db->table('impact');
        $builderImpact->where(['id' => $id,])->select('status');

        $builderUpdate = $this->db->table('impact');
        $builderUpdate->where(['id' => $id]);
        $updateData =  $builderUpdate->update($dataValues);
        if ($updateData) {
            $success = true;
            $message = 'Berhasil ubah data impact';
        } else {
            $success = false;
            $message = 'Gagal ubah data impact, silahkan coba kembali';
        }


        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    public function delete_impact()
    {
        $id = $this->request->getPost('id');
        $success = false;


        $builderDelete = $this->db->table('impact');
        $deleteData =  $builderDelete->delete(['id' => $id]);

        if ($deleteData) {
            $success = true;
            $message = 'Berhasil menghapus impact';
        } else {
            $success = false;
            $message = 'Gagal menghapus impact, silahkan coba kembali';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        return $this->response->setJSON($output);
    }
}
