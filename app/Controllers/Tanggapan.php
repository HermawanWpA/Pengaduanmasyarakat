<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ModelTanggapan;

class Tanggapan extends Controller
{

    public function __construct()
    {
        // helper('form');
        // $this->validation = \Config\Services::validation();
        // $this->session = session();


        $this->model = new ModelTanggapan;
    }

    public function index()
    {

        $data =
            [
                'title' => 'View Tables',
                'tbltanggapan' => $this->model->getALLData()
            ];
        return view('tanggapan/index', $data);
    }
    public function inputdata()
    {
        $data =
            [
                'title' => 'Input Data',
                'tbltanggapan' => $this->model->getALLData()
            ];
        return view('tanggapan/inputdata', $data);
    }
    public function tambah()
    {

        //validasi input
        if (isset($_POST['tambah'])) {
            $val = $this->validate([
                'id_tanggapan' => [
                    'label' => 'ID Pengaduan',
                    'rules' => 'required|numeric|max_length[12]|is_unique[tbltanggapan.id_tanggapan]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong.',
                        'numeric' => '{field} hanya boleh angka',
                        'is_unique' => '{field} Sudah terdaftar.'
                    ]

                ],
                'id_pengaduan' => [
                    'label' => 'pengaduan Pelapor',
                    'rules' => 'required|min_length[3][tbltanggapan.id_pengaduan]',
                    'errors' => [
                        'required' => '{field} minimal 3 digit.',
                    ]
                ],
                'tanggal_tanggapan' => [
                    'label' => 'Tanggal Tanggapan',
                    'rules' => 'required|min_length[1][tbltanggapan.tanggal_tanggapan]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong.',
                    ]
                ],
                'tanggapan' => [
                    'label' => 'Tanggapan',
                    'rules' => 'required|max_length[12][tbltanggapan.tanggapan]',
                    'errors' => [
                        'required' => '{field} tidak boleh kosong.',
                    ]
                ],

            ]);
            if (!$val) {
                session()->setFlashdata('err', \Config\Services::validation()->listErrors());
                $data =
                    [
                        'title' => 'Input Data',
                        'tbltanggapan' => $this->model->getALLData()
                    ];
                return view('tanggapan/inputdata', $data);
            } else {

                $data = [
                    'id_tanggapan' => $this->request->getPost('id_tanggapan'),
                    'id_pengaduan' => $this->request->getPost('id_pengaduan'),
                    'tanggal_tanggapan' => $this->request->getPost('tanggal_tanggapan'),
                    'tanggapan' => $this->request->getPost('tanggapan')

                ];


                //insert data
                $success = $this->model->tambah($data);
                if ($success) {
                    session()->setflashdata('massage', 'Data Berhasil Ditambahkan');
                    return redirect()->to(base_url('tanggapan/inputdata'));
                }
            }
        } else {
            return redirect()->to(base_url('tanggapan/inputdata'));
        }
    }
    public function hapus($id_pengaduan)
    {
        $success = $this->model->hapus($id_pengaduan);
        if ($success) {
            session()->setflashdata('massage', 'Data Berhasil Dihapus');
            return redirect()->to(base_url('tanggapan/inputdata'));
        }
    }
}
