<?php

namespace App\Controllers\api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;

    function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
    }

    public function register()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $no_telp = $this->request->getPost('notelp');
        $nama = $this->request->getPost('nama');
        $nik = $this->request->getPost('nik');
        $alamat = $this->request->getPost('alamat');
        $user_level = $this->request->getPost('user_level');

        $builderUsers = $this->db->table('user');
        $builderUsers->where(['email' => $email,]);
        $queryUsers    =  $builderUsers->get();
        // $db = db_connect('default');

        if (empty($queryUsers->getNumRows())) {
            // $dataValues['id'] = $db->insertID();
            $dataValues['email'] = $email;
            $dataValues['password'] = md5($password);
            $dataValues['nama'] = $nama;
            $dataValues['nik'] = $nik;
            $dataValues['alamat'] = $alamat;
            $dataValues['no_telp'] = $no_telp;
            $dataValues['user_level'] = 2;
            $tgl_buat = date('Y-m-d H:i:s');
            $dataValues['date_created'] = $tgl_buat;

            $builderInserts = $this->db->table('user');
            $insertDatas =  $builderInserts->insert($dataValues);
            if ($insertDatas) {
                $success = true;
                $message = 'Berhasil Melakukan Registrasi, silahkan login';
            } else {
                $success = false;
                $message = 'Gagal Melakukan Registrasi';
            }
        } else {
            $message = 'Akun Sudah Terdaftar, silahkan coba kembali';
        }

        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    public function login()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $builderUsers = $this->db->table('user');
        $builderUsers->where(['email' => $email, 'password' => md5($password)])->select('email, nama, user_level, no_telp, alamat');
        $queryUsers    =  $builderUsers->get();

        if ($queryUsers->getNumRows() > 0) {
            $success = true;
            $message = 'Berhasil melakukan login';
            $data_user = $queryUsers->getRowArray();
        } else {
            $success = true;
            $message = 'Kombinasi email dan password salah';
            $data_user = [];
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_user'] = $data_user;

        return $this->response->setJSON($output);
    }
}
