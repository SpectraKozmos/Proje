<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

class Kategori extends CI_Controller {

    private $mongodb;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('mongodb');
        
        if (!$this->session->userdata('logged_in')) {
            redirect('yonetici');
        }

        $this->mongodb = get_mongodb_connection();
    }

    public function index()
    {
        try {
            $data['get_category'] = $this->get_categories(); // MongoDB'den kategorileri al
            $data['content'] = "g_kategori";
            $this->load->view('sablon', $data);
        } catch (Exception $e) {
            show_error('Kategori listesi alınamadı: ' . $e->getMessage());
        }
    }

    private function get_categories()
    {
        try {
            $veritabani = "kullanicilar";
            $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kategori');
            $kategoriler = $koleksiyon->find()->toArray();
            
            // MongoDB dökümanlarını stdClass objelerine dönüştür
            return array_map(function($kategori) {
                $obj = new stdClass();
                $obj->_id = (string)$kategori->_id;
                $obj->category_code = $kategori->category_code;
                $obj->category_name = $kategori->category_name;
                return $obj;
            }, $kategoriler);
        } catch (Exception $e) {
            log_message('error', 'Kategori listesi alınamadı: ' . $e->getMessage());
            throw $e;
        }
    }

    public function add()
    {
        $this->form_validation->set_rules('category_code', 'Kategori Kodu', 'required');
        $this->form_validation->set_rules('category_name', 'Kategori Adı', 'required|min_length[3]|max_length[50]');

        if ($this->form_validation->run() == TRUE) {
            try {
                $veritabani = "kullanicilar";
                $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kategori');
                
                $yeni_kategori = [
                    'category_code' => $this->input->post('category_code'),
                    'category_name' => $this->input->post('category_name'),
                    'created_at' => new MongoDB\BSON\UTCDateTime(time() * 1000)
                ];

                $result = $koleksiyon->insertOne($yeni_kategori);
                
                if ($result->getInsertedCount() > 0) {
                    $this->session->set_flashdata('message', 'Kategori başarıyla eklendi');
                } else {
                    $this->session->set_flashdata('message', 'Kategori eklenirken bir hata oluştu');
                }
            } catch (Exception $e) {
                log_message('error', 'Kategori eklenemedi: ' . $e->getMessage());
                $this->session->set_flashdata('message', 'Kategori eklenirken bir hata oluştu: ' . $e->getMessage());
            }
            redirect('kategori');
        } else {
            $data['content'] = "g_kategori";
            $this->load->view('sablon', $data);
        }
    }

    public function update()
    {
        $this->form_validation->set_rules('category_code', 'Kategori Kodu', 'required');
        $this->form_validation->set_rules('category_name', 'Kategori Adı', 'required|min_length[3]|max_length[50]');

        if ($this->form_validation->run() == TRUE) {
            try {
                $veritabani = "kullanicilar";
                $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kategori');
                
                $id = $this->input->post('category_code_lama');
                
                $data = [
                    'category_code' => $this->input->post('category_code'),
                    'category_name' => $this->input->post('category_name'),
                    'updated_at' => new MongoDB\BSON\UTCDateTime(time() * 1000)
                ];

                $sonuc = $koleksiyon->updateOne(
                    ['_id' => new ObjectId($id)],
                    ['$set' => $data]
                );

                if ($sonuc->getModifiedCount() > 0) {
                    $this->session->set_flashdata('message', 'Kategori başarıyla güncellendi');
                } else {
                    $this->session->set_flashdata('message', 'Güncelleme sırasında bir hata oluştu');
                }
            } catch (Exception $e) {
                log_message('error', 'Kategori güncellenemedi: ' . $e->getMessage());
                $this->session->set_flashdata('message', 'Güncelleme sırasında bir hata oluştu: ' . $e->getMessage());
            }
            redirect('kategori');
        } else {
            $data['content'] = "g_kategori";
            $this->load->view('sablon', $data);
        }
    }

    public function delete_category($id)
    {
        try {
            $veritabani = "kullanicilar";
            $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kategori');

            $sonuc = $koleksiyon->deleteOne(['_id' => new ObjectId($id)]);

            if ($sonuc->getDeletedCount() > 0) {
                $this->session->set_flashdata('message', 'Kategori başarıyla silindi');
            } else {
                $this->session->set_flashdata('message', 'Silme işlemi sırasında bir hata oluştu');
            }
        } catch (Exception $e) {
            log_message('error', 'Kategori silinemedi: ' . $e->getMessage());
            $this->session->set_flashdata('message', 'Silme işlemi sırasında bir hata oluştu: ' . $e->getMessage());
        }
        redirect('kategori');
    }

    public function edit_category($id)
    {
        try {
            $veritabani = "kullanicilar";
            $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kategori');

            $kategori = $koleksiyon->findOne(['_id' => new ObjectId($id)]);
            
            if ($kategori) {
                $response = [
                    '_id' => (string)$kategori->_id,
                    'category_code' => $kategori->category_code,
                    'category_name' => $kategori->category_name
                ];
                echo json_encode($response);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Kategori bulunamadı']);
            }
        } catch (Exception $e) {
            log_message('error', 'Kategori bulunamadı: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Kategori bulunamadı: ' . $e->getMessage()]);
        }
    }
}

/* End of file Kategori.php */
/* Location: ./application/controllers/Kategori.php */