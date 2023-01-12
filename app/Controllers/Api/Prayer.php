<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Prayer extends BaseController
{
    use ResponseTrait;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
    }

    public function get_list_prayer()
    {
        $success = false;
        $message = 'Gagal Proses Data';
        $data_prayer = [];

        $user_id = $this->request->getPost('user_id');
        $panti_id = $this->request->getPost('panti_id');

        $builderPrayer = $this->db->table('prayer');
        $builderPrayer->select('prayer.*, u.email,u.image as fotoProfil, u.nama as nama_donatur, panti.nama as nama_panti, panti.id as id_panti, donasi.id as id_donasi');
        $builderPrayer->join('user u', 'u.id = prayer.user_id', 'left');
        $builderPrayer->join('panti', 'panti.id = prayer.panti_id', 'left');
        $builderPrayer->join('donasi', 'donasi.id = prayer.donasi_id', 'left');

        if ($user_id) {
            $builderPrayer->where(['prayer.user_id' => $user_id]);
            $queryPrayer    =  $builderPrayer->get();

            if ($queryPrayer->getNumRows() > 0) {
                $success = true;
                $message = 'get list prayer success';
                $data_prayer = $queryPrayer->getResultArray();
            } else {
                $success = false;
                $message = 'get list prayer failed';
            }
            $output['success'] = $success;
            $output['message'] = $message;
            $output['data_prayer'] = $data_prayer;

            return $this->response->setJSON($output);
        }

        if ($panti_id) {
            $builderPrayer->where(['prayer.panti_id' => $panti_id]);
            $queryPrayer    =  $builderPrayer->get();

            if ($queryPrayer->getNumRows() > 0) {
                $success = true;
                $message = 'get list prayer success';
                $data_prayer = $queryPrayer->getResultArray();
            } else {
                $success = false;
                $message = 'get list prayer failed';
            }
            $output['success'] = $success;
            $output['message'] = $message;
            $output['data_prayer'] = $data_prayer;

            return $this->response->setJSON($output);
        }

        $queryPrayer    = $builderPrayer->get();
        if ($queryPrayer->getNumRows() > 0) {
            $success = true;
            $message = 'get list prayer success';
            $data_prayer = $queryPrayer->getResultArray();
        } else {
            $success = false;
            $message = 'get list prayer failed';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_prayer'] = $data_prayer;

        return $this->response->setJSON($output);
    }

    public function insert_prayer()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $user_id = $this->request->getPost('user_id');
        $messageRequest = $this->request->getPost('message');
        $panti_id = $this->request->getPost('panti_id');
        $total_amen = $this->request->getPost('total_amen');
        $donasi_id = $this->request->getPost('donasi_id');


        $dataValues['user_id'] = $user_id;
        $dataValues['message'] = $messageRequest;
        $dataValues['panti_id'] = $panti_id;
        $dataValues['total_amen'] = $total_amen;
        $dataValues['donasi_id'] = $donasi_id;
        $tgl_buat = date('Y-m-d H:i:s');
        $dataValues['date_created'] = $tgl_buat;

        $builderInserts = $this->db->table('prayer');
        $insertDatas =  $builderInserts->insert($dataValues);

        if ($insertDatas) {
            $success = true;
            $message = 'Berhasil menambahkan doa, terimakasih atas doa yang telah Anda berikan';
        } else {
            $success = false;
            $message = 'Gagal menambahkan doa, silahkan coba kembali';
        }


        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    public function update_prayer()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $id = $this->request->getPost('id');
        $messageRequest = $this->request->getPost('message');
        $panti_id = $this->request->getPost('panti_id');
        $total_amen = $this->request->getPost('total_amen');
        $donasi_id = $this->request->getPost('donasi_id');


        $dataValues['id'] = $id;
        $dataValues['message'] = $messageRequest;
        $dataValues['panti_id'] = $panti_id;
        $dataValues['total_amen'] = $total_amen;
        $dataValues['donasi_id'] = $donasi_id;
        $tgl_buat = date('Y-m-d H:i:s');
        $dataValues['date_created'] = $tgl_buat;

        $builderPrayer = $this->db->table('prayer');
        $builderPrayer->where(['id' => $id,])->select('status');

        $builderUpdate = $this->db->table('prayer');
        $builderUpdate->where(['id' => $id]);
        $updateData =  $builderUpdate->update($dataValues);
        if ($updateData) {
            $success = true;
            $message = 'Berhasil ubah data prayer';
        } else {
            $success = false;
            $message = 'Gagal ubah data prayer, silahkan coba kembali';
        }


        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    public function delete_prayer()
    {
        $id = $this->request->getPost('id');
        $success = false;
        $message = 'Gagal proses data';


        $builderDelete = $this->db->table('prayer');
        $deleteData =  $builderDelete->delete(['id' => $id]);

        if ($deleteData) {
            $success = true;
            $message = 'Berhasil menghapus doa';
        } else {
            $success = false;
            $message = 'Gagal menghapus doa, silahkan coba kembali';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        return $this->response->setJSON($output);
    }
}
