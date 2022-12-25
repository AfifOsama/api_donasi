<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Donasi extends BaseController
{
    use ResponseTrait;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
    }

    public function get_list_donasi()
    {
        $success = false;
        $message = 'Gagal Proses Data';
        $data_donasi = [];

        $user_id = $this->request->getPost('user_id');

        $builderDonasi = $this->db->table('donasi');
        $builderDonasi->where(['user_id' => $user_id])->select('donasi.*, u.email, u.nama as nama_donatur, jenis.nama_jenis,satuan.nama_satuan, panti.nama as nama_panti, panti.alamat');
        $builderDonasi->join('user u', 'u.id = donasi.user_id', 'left');
        $builderDonasi->join('jenis_donasi jenis', 'jenis.id = donasi.jenis_donasi_id', 'left');
        $builderDonasi->join('satuan_donasi satuan', 'satuan.id = donasi.satuan_donasi_id', 'left');
        $builderDonasi->join('panti', 'panti.id = donasi.panti_id', 'left');
        $queryDonasi    =  $builderDonasi->get();

        if ($queryDonasi->getNumRows() > 0) {
            $success = true;
            $message = 'get list donasi success';
            $data_donasi = $queryDonasi->getResultArray();
        } else {
            $success = false;
            $message = 'get list donasi failed';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_donasi'] = $data_donasi;

        return $this->response->setJSON($output);
    }

    public function insert_donasi()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $user_id = $this->request->getPost('user_id');
        $nama_donasi = $this->request->getPost('nama_donasi');
        $panti_id = $this->request->getPost('panti_id');
        $jenis_donasi_id = $this->request->getPost('jenis_donasi_id');
        $total = $this->request->getPost('total');
        $satuan_donasi_id = $this->request->getPost('satuan_donasi_id');
        $is_appear = $this->request->getPost('is_appear');
        $status = $this->request->getPost('status');


        $dataValues['user_id'] = $user_id;
        $dataValues['nama_donasi'] = $nama_donasi;
        $dataValues['panti_id'] = $panti_id;
        $dataValues['jenis_donasi_id'] = $jenis_donasi_id;
        $dataValues['total'] = $total;
        $dataValues['is_appear'] = $is_appear;
        $dataValues['satuan_donasi_id'] = $satuan_donasi_id;
        $tgl_buat = date('Y-m-d H:i:s');
        $dataValues['date_created'] = $tgl_buat;
        $dataValues['status'] = $status;

        $builderInserts = $this->db->table('donasi');
        $insertDatas =  $builderInserts->insert($dataValues);

        if ($insertDatas) {
            $success = true;
            $message = 'Berhasil menambahkan donasi, terimakasih atas kebaikan yang telah Anda buat';
        } else {
            $success = false;
            $message = 'Gagal menambahkan donasi, silahkan coba kembali';
        }


        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    public function update_donasi()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $id = $this->request->getPost('id');
        $nama_donasi = $this->request->getPost('nama_donasi');
        $panti_id = $this->request->getPost('panti_id');
        $jenis_donasi_id = $this->request->getPost('jenis_donasi_id');
        $total = $this->request->getPost('total');
        $satuan_donasi_id = $this->request->getPost('satuan_donasi_id');
        $is_appear = $this->request->getPost('is_appear');
        $status = $this->request->getPost('status');


        $dataValues['id'] = $id;
        $dataValues['nama_donasi'] = $nama_donasi;
        $dataValues['panti_id'] = $panti_id;
        $dataValues['jenis_donasi_id'] = $jenis_donasi_id;
        $dataValues['total'] = $total;
        $dataValues['is_appear'] = $is_appear;
        $dataValues['satuan_donasi_id'] = $satuan_donasi_id;
        $tgl_buat = date('Y-m-d H:i:s');
        $dataValues['date_created'] = $tgl_buat;
        $dataValues['status'] = $status;

        $builderDonasi = $this->db->table('donasi');
        $builderDonasi->where(['id' => $id,])->select('status');

        $queryDonasi    =  $builderDonasi->get();
        $rowDonasi = $queryDonasi->getRowArray();

        if ($rowDonasi['status'] == 0) {
            $builderUpdate = $this->db->table('donasi');
            $builderUpdate->where(['id' => $id]);
            $updateData =  $builderUpdate->update($dataValues);
            if ($updateData) {
                $success = true;
                $message = 'Berhasil ubah data donasi';
            } else {
                $success = false;
                $message = 'Gagal ubah data donasi, silahkan coba kembali';
            }
        } else {
            $success = false;
            $message = 'Mohon maaf tidak bisa ubah data donasi dikarenakan donasi sudah diproses';
        }

        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }
}
