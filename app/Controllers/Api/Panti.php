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

        $jenis_donasi = $this->request->getPost('jenis_donasi');
        $pengelola_id = $this->request->getPost('pengelola_id');

        $builderPanti = $this->db->table('panti');
        $builderPanti->select('panti.*');

        if ($pengelola_id) {
            $builderPanti->where(['pengelola_id' => $pengelola_id]);
            $queryPanti    =  $builderPanti->get();
            if ($queryPanti->getNumRows() > 0) {
                foreach ($queryPanti->getResult() as $key) {

                    $arraypushJenis = [];
                    $jenis = explode(",", $key->jenis_donasi);
                    foreach ($jenis as $j) {
                        $full_url = base_url() . $j;
                        array_push($arraypushJenis, $j);
                        $key->jenis_donasi = $arraypushJenis;
                    }
                    $key->logo = base_url() .  $key->logo;
                }
                $success = true;
                $message = 'get list panti success';
                $data_panti = $queryPanti->getResultArray();
            } else {
                $success = false;
                $message = 'get list panti failed';
            }

            $output['success'] = $success;
            $output['message'] = $message;
            $output['data_panti'] = $data_panti;

            return $this->response->setJSON($output);
        }

        if ($jenis_donasi) {
            $builderPanti->like(['jenis_donasi' => $jenis_donasi]);
            $queryPanti    =  $builderPanti->get();
            if ($queryPanti->getNumRows() > 0) {
                foreach ($queryPanti->getResult() as $key) {

                    $arraypushJenis = [];
                    $jenis = explode(",", $key->jenis_donasi);
                    foreach ($jenis as $j) {
                        $full_url = base_url() . $j;
                        array_push($arraypushJenis, $j);
                        $key->jenis_donasi = $arraypushJenis;
                    }
                    $key->logo = base_url() .  $key->logo;
                }
                $success = true;
                $message = 'get list panti success';
                $data_panti = $queryPanti->getResultArray();
            } else {
                $success = false;
                $message = 'get list panti failed';
            }

            $output['success'] = $success;
            $output['message'] = $message;
            $output['data_panti'] = $data_panti;

            return $this->response->setJSON($output);
        }

        $queryPanti    =  $builderPanti->get();
        if ($queryPanti->getNumRows() > 0) {
            foreach ($queryPanti->getResult() as $key) {

                $arraypushJenis = [];
                $jenis = explode(",", $key->jenis_donasi);
                foreach ($jenis as $j) {
                    $full_url = base_url() . $j;
                    array_push($arraypushJenis, $j);
                    $key->jenis_donasi = $arraypushJenis;
                }

                $key->logo = base_url() .  $key->logo;
            }
            $success = true;
            $message = 'get list panti success';
            $data_panti = $queryPanti->getResultArray();
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
        $logo = $this->request->getPost('logo');
        $alamat = $this->request->getPost('alamat');
        $plan = $this->request->getPost('plan');
        $longitude = $this->request->getPost('longitude');
        $latitude = $this->request->getPost('latitude');
        $dokumen_sertifikat = $this->request->getPost('dokumen_sertifikat');
        $pengelola_id = $this->request->getPost('pengelola_id');
        $total_donasi = $this->request->getPost('total_donasi');
        $image = $this->request->getPost('image');

        $path = $this->cek_directory_upload();
        $namaGmbr = date('YmdHis') . ".jpg";
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
        $imageName = FCPATH . '/' . $path . $namaGmbr;

        $namaGmbr = $path . $namaGmbr;
        if ($imageName) {
            $cleaned = strval(str_replace("\0", "", $imageName));

            file_put_contents($cleaned, $imageData);

            $dataValues['nama'] = $nama;
            $dataValues['description'] = $description;
            $dataValues['images'] = $namaGmbr;
            $dataValues['logo'] = $logo;
            $dataValues['dokumen_sertifikat'] = $dokumen_sertifikat;
            $dataValues['alamat'] = $alamat;
            $dataValues['plan'] = $plan;
            $dataValues['longitude'] = $longitude;
            $dataValues['latitude'] = $latitude;
            $tgl_buat = date('Y-m-d H:i:s');
            $dataValues['date_created'] = $tgl_buat;
            $dataValues['pengelola_id'] = $pengelola_id;
            $dataValues['total_donasi'] = $total_donasi;

            $builderInserts = $this->db->table('panti');
            $insertDatas =  $builderInserts->insert($dataValues);

            if ($insertDatas) {
                $success = true;
                $message = 'Berhasil mendaftarkan panti';
            } else {
                $success = false;
                $message = 'Gagal Melakukan Registrasi, silahkan coba kembali';
            }
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
        $logo = $this->request->getPost('logo');
        $alamat = $this->request->getPost('alamat');
        $plan = $this->request->getPost('plan');
        $longitude = $this->request->getPost('longitude');
        $latitude = $this->request->getPost('latitude');
        $dokumen_sertifikat = $this->request->getPost('dokumen_sertifikat');
        $pengelola_id = $this->request->getPost('pengelola_id');
        $total_donasi = $this->request->getPost('total_donasi');
        $image = $this->request->getPost('image');


        $path = $this->cek_directory_upload();
        $namaGmbr = date('YmdHis') . ".jpg";
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
        $imageName = FCPATH . '/' . $path . $namaGmbr;

        $namaGmbr = $path . $namaGmbr;
        if ($imageName) {
            $cleaned = strval(str_replace("\0", "", $imageName));

            file_put_contents($imageName, $cleaned);

            $dataValues['nama'] = $nama;
            $dataValues['description'] = $description;
            $dataValues['alamat'] = $alamat;
            $dataValues['plan'] = $plan;


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
        } else {
            $success = false;
            $message = 'Gagal upload data panti, silahkan coba kembali';
        }

        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    private function cek_directory_upload()
    {
        $tanggal = Date('d');
        $bulan      = Date('m');
        $tahun   = Date('Y');
        $path = "/uploads/foto_panti/";
        if (!is_dir(FCPATH . '/' . $path)) {
            mkdir(FCPATH . '/' . $path, 0777, TRUE);
        }
        return $path;
    }

    private function cek_directory_upload_file()
    {
        $tanggal = Date('d');
        $bulan      = Date('m');
        $tahun   = Date('Y');
        $path = "/uploads/file_panti/" . $tahun . '/' . $bulan . '/' . $tanggal . '/';
        if (!is_dir(FCPATH . '/' . $path)) {
            mkdir(FCPATH . '/' . $path, 0777, TRUE);
        }
        return $path;
    }

    public function upload_foto()
    {
        $success = false;
        $message = 'Upload Photo Failed';
        $data_file = "";

        $array_image = $this->request->getFiles('upload_image');
        $id = $this->request->getPost('id');
        $validationRule = [
            "upload_image" => [
                'rules' => 'uploaded[upload_image]'
                    . '|mime_in[upload_image,image/jpeg,image/png,application/pdf]'
                    . '|max_size[upload_image,5000]',
            ],
        ];
        if (!empty($array_image)) {
            foreach ($array_image["upload_image"] as $row) {
                if (!$row->isValid()) {
                    if ($this->validator->getErrors()) {
                        $message = 'Upload Photo Failed,Max 5MB';
                    }
                    $output['success'] = $success;
                    $output['message'] = $message;
                    return $this->response->setJSON($output);
                    exit;
                }

                if ($row->getError() == 4) {
                    $namaIcon = '';
                } else {
                    $path = "./uploads/foto_panti/";
                    if (!is_dir($path)) {
                        mkdir($path, 0777, TRUE);
                        fopen($path . "/index.php", "w");
                    }
                    //generate nama sampul random
                    $namaIcon = $row->getRandomName();
                    //pindahkan file ke folder img
                    $row->move('uploads/foto_panti/', $namaIcon);
                    $full_url = "/uploads/foto_panti/" . $namaIcon;
                    $data_file = $data_file . $full_url . ",";

                    $data['images'] = $data_file;

                    $builderUpdate = $this->db->table('panti');
                    $builderUpdate->where(['id' => $id]);
                    $updateData =  $builderUpdate->update($data);
                    if ($updateData) {
                        $success = true;
                        $message = 'Foto berhasil diupload';
                    } else {
                        $success = false;
                        $message = 'Gagal upload foto';
                    }
                }
            }
        }

        $output['success'] = $success;
        $output['message'] = $message;
        $output['data_file'] = $data_file;

        return $this->response->setJSON($output);
    }

    public function update_jumlah_donatur()
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
        $total_donasi = $this->request->getPost('total_donasi');
        $dokumen_sertifikat = $this->request->getPost('dokumen_sertifikat');

        $path = $this->cek_directory_upload();
        $namaGmbr = date('YmdHis') . ".jpg";
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
        $imageName = FCPATH . '/' . $path . $namaGmbr;

        $namaGmbr = $path . $namaGmbr;
        if ($imageName) {
            $cleaned = strval(str_replace("\0", "", $imageName));

            file_put_contents($imageName, $cleaned);

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
            $dataValues['total_donasi'] = $total_donasi;

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
        } else {
            $success = false;
            $message = 'Gagal upload data panti, silahkan coba kembali';
        }

        $output['success'] = $success;
        $output['message'] = $message;

        return $this->response->setJSON($output);
    }

    public function delete_panti()
    {
        $id = $this->request->getPost('id');
        $success = false;


        $builderDelete = $this->db->table('panti');
        $deleteData =  $builderDelete->delete(['id' => $id]);

        if ($deleteData) {
            $success = true;
            $message = 'Berhasil menghapus panti';
        } else {
            $success = false;
            $message = 'Gagal menghapus panti, silahkan coba kembali';
        }

        $output['success'] = $success;
        $output['message'] = $message;
        return $this->response->setJSON($output);
    }
}


// public function get_list_panti()
// {
//     $success = false;
//     $message = 'Gagal Proses Data';
//     $data_panti = [];

//     $jenis_donasi = $this->request->getPost('jenis_donasi');
//     $pengelola_id = $this->request->getPost('pengelola_id');

//     $builderPanti = $this->db->table('panti');
//     $builderPanti->select('panti.*');

//     if ($pengelola_id) {
//         $builderPanti->where(['pengelola_id' => $pengelola_id]);
//         $queryPanti    =  $builderPanti->get();
//         if ($queryPanti->getNumRows() > 0) {
//             foreach ($queryPanti->getResult() as $key) {
//                 $images = explode(",", $key->images);
//                 $arraypushImage = [];
//                 foreach ($images as $img) {
//                     $full_url = base_url() . $img;
//                     array_push($arraypushImage, $img);
//                     $key->images = $arraypushImage;
//                 }
//                 unset($key->images[count($key->images) - 1]);
//                 $arraypushJenis = [];
//                 $jenis = explode(",", $key->jenis_donasi);
//                 foreach ($jenis as $j) {
//                     $full_url = base_url() . $j;
//                     array_push($arraypushJenis, $j);
//                     $key->jenis_donasi = $arraypushJenis;
//                 }
//                 $key->logo = base_url() .  $key->logo;
//             }
//             $success = true;
//             $message = 'get list panti success';
//             $data_panti = $queryPanti->getResultArray();
//         } else {
//             $success = false;
//             $message = 'get list panti failed';
//         }

//         $output['success'] = $success;
//         $output['message'] = $message;
//         $output['data_panti'] = $data_panti;

//         return $this->response->setJSON($output);
//     }

//     if ($jenis_donasi) {
//         $builderPanti->like(['jenis_donasi' => $jenis_donasi]);
//         $queryPanti    =  $builderPanti->get();
//         if ($queryPanti->getNumRows() > 0) {
//             foreach ($queryPanti->getResult() as $key) {
//                 $images = explode(",", $key->images);
//                 $arraypushImage = [];
//                 foreach ($images as $img) {
//                     $full_url = base_url() . $img;
//                     array_push($arraypushImage, $img);
//                     $key->images = $arraypushImage;
//                 }
//                 unset($key->images[count($key->images) - 1]);
//                 $arraypushJenis = [];
//                 $jenis = explode(",", $key->jenis_donasi);
//                 foreach ($jenis as $j) {
//                     $full_url = base_url() . $j;
//                     array_push($arraypushJenis, $j);
//                     $key->jenis_donasi = $arraypushJenis;
//                 }
//                 $key->logo = base_url() .  $key->logo;
//             }
//             $success = true;
//             $message = 'get list panti success';
//             $data_panti = $queryPanti->getResultArray();
//         } else {
//             $success = false;
//             $message = 'get list panti failed';
//         }

//         $output['success'] = $success;
//         $output['message'] = $message;
//         $output['data_panti'] = $data_panti;

//         return $this->response->setJSON($output);
//     }

//     $queryPanti    =  $builderPanti->get();
//     if ($queryPanti->getNumRows() > 0) {
//         foreach ($queryPanti->getResult() as $key) {

//             $images = explode(",", $key->images);
//             $arraypush = [];
//             foreach ($images as $img) {
//                 $full_url = base_url() . $img;
//                 array_push($arraypush, $img);
//                 $key->images = $arraypush;
//             }
//             unset($key->images[count($key->images) - 1]);

//             $arraypushJenis = [];
//             $jenis = explode(",", $key->jenis_donasi);
//             foreach ($jenis as $j) {
//                 $full_url = base_url() . $j;
//                 array_push($arraypushJenis, $j);
//                 $key->jenis_donasi = $arraypushJenis;
//             }

//             $key->logo = base_url() .  $key->logo;
//         }
//         $success = true;
//         $message = 'get list panti success';
//         $data_panti = $queryPanti->getResultArray();
//     } else {
//         $success = false;
//         $message = 'get list panti failed';
//     }

//     $output['success'] = $success;
//     $output['message'] = $message;
//     $output['data_panti'] = $data_panti;

//     return $this->response->setJSON($output);
// }