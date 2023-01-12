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

    public function registration()
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
        $image = $this->request->getPost('image');

        $path = $this->cek_directory_upload();
        $namaGmbr = date('YmdHis') . ".jpg";
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
        $imageName = FCPATH . '/' . $path . $namaGmbr;

        $namaGmbr = $path . $namaGmbr;

        $builderUsers = $this->db->table('user');
        $builderUsers->where(['email' => $email,]);
        $queryUsers    =  $builderUsers->get();

        if (empty($queryUsers->getNumRows())) {
            if ($imageName) {
                file_put_contents($imageName, $imageData);
                $dataValues['image'] = $namaGmbr;
                $dataValues['email'] = $email;
                $dataValues['password'] = md5($password);
                $dataValues['nama'] = $nama;
                $dataValues['nik'] = $nik;
                $dataValues['alamat'] = $alamat;
                $dataValues['no_telp'] = $no_telp;
                $dataValues['user_level'] = $user_level;
                $tgl_buat = date('Y-m-d H:i:s');
                $dataValues['date_created'] = $tgl_buat;

                $builderInserts = $this->db->table('user');
                $insertDatas =  $builderInserts->insert($dataValues);

                if ($insertDatas) {
                    $success = true;
                    $message = 'Berhasil Melakukan Registrasi, silahkan login';
                } else {
                    $success = false;
                    $message = 'Gagal Melakukan Registrasi, silahkan coba kembali';
                }
            } else {
                $success = false;
                $message = 'Gagal upload foto profile, silahkan coba kembali';
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
        $builderUsers->where(['email' => $email, 'password' => md5($password)])->select('id,email, nama, user_level, no_telp, alamat');
        $queryUsers    =  $builderUsers->get();

        if ($queryUsers->getNumRows() > 0) {
            $success = true;
            $message = 'Berhasil melakukan login';
            $data_user = $queryUsers->getRowArray();
        } else {
            $success = false;
            $message = 'Kombinasi email dan password salah, silahkan coba kembali';
            $data_user = null;
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_user'] = $data_user;

        return $this->response->setJSON($output);
    }

    private function cek_directory_upload()
    {
        $tanggal = Date('d');
        $bulan      = Date('m');
        $tahun   = Date('Y');
        $path = "/uploads/foto_profil/" . $tahun . '/' . $bulan . '/' . $tanggal . '/';
        if (!is_dir(FCPATH . '/' . $path)) {
            mkdir(FCPATH . '/' . $path, 0777, TRUE);
        }
        return $path;
    }
}
