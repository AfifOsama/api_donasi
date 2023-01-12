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
        $panti_id = $this->request->getPost('panti_id');

        $builderDonasi = $this->db->table('donasi');
        $builderDonasi->select('donasi.*, u.email, u.nama as nama_donatur, jenis.nama_jenis,satuan.nama_satuan, panti.nama as nama_panti, panti.alamat, u.image as fotoProfil');
        $builderDonasi->join('user u', 'u.id = donasi.user_id', 'left');
        $builderDonasi->join('jenis_donasi jenis', 'jenis.id = donasi.jenis_donasi_id', 'left');
        $builderDonasi->join('satuan_donasi satuan', 'satuan.id = donasi.satuan_donasi_id', 'left');
        $builderDonasi->join('panti', 'panti.id = donasi.panti_id', 'left');

        if ($user_id) {
            $builderDonasi->where(['user_id' => $user_id]);
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

        if ($panti_id) {
            $builderDonasi->where(['panti_id' => $panti_id]);
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

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_donasi'] = $data_donasi;

        return $this->response->setJSON($output);
    }

    private function cek_directory_upload()
    {
        $tanggal = Date('d');
        $bulan      = Date('m');
        $tahun   = Date('Y');
        // $path = "/uploads/foto_donasi/" . $tahun . '/' . $bulan . '/' . $tanggal . '/';
        $path = "/uploads/foto_donasi/";
        if (!is_dir(FCPATH . '/' . $path)) {
            mkdir(FCPATH . '/' . $path, 0777, TRUE);
        }
        return $path;
    }

    public function insert_donasi()
    {
        $success = false;
        $message = 'Gagal Proses Data';

        $user_id = $this->request->getPost('user_id');
        $nama_donasi = $this->request->getPost('nama_donasi');
        $atas_nama = $this->request->getPost('atas_nama');
        $kurir = $this->request->getPost('kurir');
        $no_resi = $this->request->getPost('no_resi');
        $panti_id = $this->request->getPost('panti_id');
        $jenis_donasi_id = $this->request->getPost('jenis_donasi_id');
        $total = $this->request->getPost('total');
        $satuan_donasi_id = $this->request->getPost('satuan_donasi_id');
        $is_appear = $this->request->getPost('is_appear');
        $status = $this->request->getPost('status');
        $image = $this->request->getPost('image');

        $path = $this->cek_directory_upload();
        $namaGmbr = date('YmdHis') . ".jpg";
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
        $imageName = FCPATH . '/' . $path . $namaGmbr;

        $namaGmbr = $path . $namaGmbr;
        if ($imageName) {
            $cleaned = strval(str_replace("\0", "", $imageName));
            file_put_contents($cleaned, $imageData);
            $dataValues['image'] = $namaGmbr;
            $dataValues['user_id'] = $user_id;
            $dataValues['no_resi'] = $no_resi;
            $dataValues['kurir'] = $kurir;
            $dataValues['atas_nama'] = $atas_nama;
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

            //tambah total donasi
            $builderPanti = $this->db->table('panti');
            $builderPanti->where(['id' => $panti_id]);
            $queryPanti    =  $builderPanti->get();

            if ($queryPanti->getNumRows() > 0) {
                foreach ($queryPanti->getResult() as $key) {
                    $newTotal = $key->total_donasi + 1;
                }
                $dataValuess['total_donasi'] = $newTotal;
                $builderUpdate = $this->db->table('panti');
                $builderUpdate->where(['id' => $panti_id]);
                $updateData =  $builderUpdate->update($dataValuess);
            }

            if ($insertDatas) {
                $success = true;
                $message = 'Berhasil menambahkan donasi, terimakasih atas kebaikan yang telah Anda buat';
            } else {
                $success = false;
                $message = 'Gagal menambahkan donasi, silahkan coba kembali';
            }
        } else {
            $success = false;
            $message = 'Tidak berhasil menambahkan donasi karena gagal upload file';
        }

        $output['success'] = $success;
        $output['message'] = $message;


        return $this->response->setJSON($output);
    }
    // public function insert_donasi()
    // {
    //     $success = false;
    //     $message = 'Gagal Proses Data';

    //     $user_id = $this->request->getPost('user_id');
    //     $nama_donasi = $this->request->getPost('nama_donasi');
    //     $panti_id = $this->request->getPost('panti_id');
    //     $jenis_donasi_id = $this->request->getPost('jenis_donasi_id');
    //     $total = $this->request->getPost('total');
    //     $satuan_donasi_id = $this->request->getPost('satuan_donasi_id');
    //     $is_appear = $this->request->getPost('is_appear');
    //     $status = $this->request->getPost('status');
    //     $image = $this->request->getPost('image');

    //     $path = "./uploads/foto_donasi/";
    //     if (!is_dir($path)) {
    //         mkdir($path, 0777, TRUE);
    //         fopen($path . "/index.php", "w");
    //     }
    //     //generate nama sampul random
    //     $namaIcon = $image->getRandomName();
    //     // print_r($namaIcon);
    //     // exit;
    //     //pindahkan file ke folder img
    //     $image->move('uploads/foto_donasi/', $namaIcon, true);
    //     $full_url = "/uploads/foto_donasi/" . $namaIcon;

    //     $data_file = "";
    //     $data_file = $data_file . $full_url;

    //     $dataValues['image'] = $data_file;
    //     $dataValues['user_id'] = $user_id;
    //     $dataValues['nama_donasi'] = $nama_donasi;
    //     $dataValues['panti_id'] = $panti_id;
    //     $dataValues['jenis_donasi_id'] = $jenis_donasi_id;
    //     $dataValues['total'] = $total;
    //     $dataValues['is_appear'] = $is_appear;
    //     $dataValues['satuan_donasi_id'] = $satuan_donasi_id;
    //     $tgl_buat = date('Y-m-d H:i:s');
    //     $dataValues['date_created'] = $tgl_buat;
    //     $dataValues['status'] = $status;

    //     $builderInserts = $this->db->table('donasi');
    //     $insertDatas =  $builderInserts->insert($dataValues);

    //     if ($insertDatas) {
    //         $success = true;
    //         $message = 'Berhasil menambahkan donasi, terimakasih atas kebaikan yang telah Anda buat';
    //     } else {
    //         $success = false;
    //         $message = 'Gagal menambahkan donasi, silahkan coba kembali';
    //     }


    //     $output['success'] = $success;
    //     $output['message'] = $message;

    //     return $this->response->setJSON($output);
    // }

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
