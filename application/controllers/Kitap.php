<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use MongoDB\Client;
use MongoDB\BSON\ObjectId;

class Kitap extends CI_Controller {

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

    private function get_categories()
    {
        try {
            $veritabani = "kullanicilar";
            $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kategori');
            $kategoriler = $koleksiyon->find()->toArray();
            
            return array_map(function($kategori) {
                $obj = new stdClass();
                $obj->_id = (string)$kategori->_id;
                $obj->category_code = $kategori->category_code;
                $obj->category_name = $kategori->category_name;
                return $obj;
            }, $kategoriler);
        } catch (Exception $e) {
            log_message('error', 'Kategori listesi alınamadı: ' . $e->getMessage());
            return [];
        }
    }

    private function get_books()
    {
        try {
            $veritabani = "kullanicilar";
            $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kitaplar');
            
            // Kategori bilgilerini de al (MongoDB aggregation)
            $pipeline = [
                [
                    '$lookup' => [
                        'from' => 'kategori',
                        'localField' => 'category_code',
                        'foreignField' => 'category_code',
                        'as' => 'category'
                    ]
                ],
                [
                    '$unwind' => [
                        'path' => '$category',
                        'preserveNullAndEmptyArrays' => true
                    ]
                ]
            ];
            
            $kitaplar = $koleksiyon->aggregate($pipeline)->toArray();
            
            return array_map(function($kitap) {
                $obj = new stdClass();
                $obj->_id = (string)$kitap->_id;
                $obj->book_title = $kitap->book_title;
                $obj->year = $kitap->year;
                $obj->price = $kitap->price;
                $obj->category_code = $kitap->category_code;
                $obj->category_name = isset($kitap->category) ? $kitap->category->category_name : '';
                $obj->publisher = $kitap->publisher;
                $obj->writer = $kitap->writer;
                $obj->stock = $kitap->stock;
                $obj->book_img = isset($kitap->book_img) ? $kitap->book_img : '';
                return $obj;
            }, $kitaplar);
        } catch (Exception $e) {
            log_message('error', 'Kitap listesi alınamadı: ' . $e->getMessage());
            return [];
        }
    }

    public function index()
    {
        $data['get_book'] = $this->get_books();
        foreach ($data['get_book'] as $book) {
            $seo_url = url_title($book->book_title, 'dash', TRUE);
            $book->detail_link = anchor('kitap/detay/' . $seo_url, 'Detaylar', 'class="btn btn-info btn-sm"');
        }
        $data['category'] = $this->get_categories();
        $data['content'] = "g_kitap";
        $this->load->view('sablon', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('book_title', 'Kitap Adı', 'trim|required');
        $this->form_validation->set_rules('year', 'Yıl', 'trim|required');
        $this->form_validation->set_rules('price', 'Fiyat', 'trim|required');
        $this->form_validation->set_rules('category', 'Kategori', 'trim|required');
        $this->form_validation->set_rules('publisher', 'Yayınevi', 'trim|required');
        $this->form_validation->set_rules('stock', 'Stok', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            try {
                $config['upload_path'] = './assets/gambar/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                
                $book_img = '';
                if ($_FILES['gambar']['name'] != "") {
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload('gambar')) {
                        $this->session->set_flashdata('message', $this->upload->display_errors());
                        redirect('kitap');
                        return;
                    }
                    $book_img = $this->upload->data('file_name');
                }

                $veritabani = "kullanicilar";
                $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kitaplar');
                
                $yeni_kitap = [
                    'book_title' => $this->input->post('book_title'),
                    'year' => $this->input->post('year'),
                    'price' => $this->input->post('price'),
                    'category_code' => $this->input->post('category'),
                    'publisher' => $this->input->post('publisher'),
                    'writer' => $this->input->post('writer'),
                    'stock' => $this->input->post('stock'),
                    'created_at' => new MongoDB\BSON\UTCDateTime(time() * 1000)
                ];

                if ($book_img !== '') {
                    $yeni_kitap['book_img'] = $book_img;
                }

                $result = $koleksiyon->insertOne($yeni_kitap);
                
                if ($result->getInsertedCount() > 0) {
                    $this->session->set_flashdata('message', 'Kitap başarıyla eklendi');
                } else {
                    $this->session->set_flashdata('message', 'Kitap eklenirken bir hata oluştu');
                }
            } catch (Exception $e) {
                log_message('error', 'Kitap eklenemedi: ' . $e->getMessage());
                $this->session->set_flashdata('message', 'Kitap eklenirken bir hata oluştu: ' . $e->getMessage());
            }
            redirect('kitap');
        } else {
            $data['content'] = "g_kitap";
            $this->load->view('sablon', $data);
        }
    }

    public function update()
    {
        $this->form_validation->set_rules('book_title', 'Kitap Adı', 'trim|required');
        $this->form_validation->set_rules('year', 'Yıl', 'trim|required');
        $this->form_validation->set_rules('price', 'Fiyat', 'trim|required');
        $this->form_validation->set_rules('category', 'Kategori', 'trim|required');
        $this->form_validation->set_rules('publisher', 'Yayınevi', 'trim|required');
        $this->form_validation->set_rules('stock', 'Stok', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            try {
                $veritabani = "kullanicilar";
                $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kitaplar');
                
                $id = $this->input->post('book_code');
                
                $kitap = [
                    'book_title' => $this->input->post('book_title'),
                    'year' => $this->input->post('year'),
                    'price' => $this->input->post('price'),
                    'category_code' => $this->input->post('category'),
                    'publisher' => $this->input->post('publisher'),
                    'writer' => $this->input->post('writer'),
                    'stock' => $this->input->post('stock'),
                    'updated_at' => new MongoDB\BSON\UTCDateTime(time() * 1000)
                ];

                if ($_FILES['gambar']['name'] != "") {
                    $config['upload_path'] = './assets/gambar/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    
                    $this->load->library('upload', $config);
                    
                    if (!$this->upload->do_upload('gambar')) {
                        $this->session->set_flashdata('message', $this->upload->display_errors());
                        redirect('kitap');
                        return;
                    }
                    
                    $kitap['book_img'] = $this->upload->data('file_name');
                }

                $result = $koleksiyon->updateOne(
                    ['_id' => new ObjectId($id)],
                    ['$set' => $kitap]
                );
                
                if ($result->getModifiedCount() > 0) {
                    $this->session->set_flashdata('message', 'Kitap başarıyla güncellendi');
                } else {
                    $this->session->set_flashdata('message', 'Kitap güncellenirken bir hata oluştu');
                }
            } catch (Exception $e) {
                log_message('error', 'Kitap güncellenemedi: ' . $e->getMessage());
                $this->session->set_flashdata('message', 'Kitap güncellenirken bir hata oluştu: ' . $e->getMessage());
            }
            redirect('kitap');
        } else {
            $data['content'] = "g_kitap";
            $this->load->view('sablon', $data);
        }
    }

    public function delete($id)
    {
        try {
            $veritabani = "kullanicilar";
            $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kitaplar');

            $result = $koleksiyon->deleteOne(['_id' => new ObjectId($id)]);
            
            if ($result->getDeletedCount() > 0) {
                $this->session->set_flashdata('message', 'Kitap başarıyla silindi');
            } else {
                $this->session->set_flashdata('message', 'Kitap silinirken bir hata oluştu');
            }
        } catch (Exception $e) {
            log_message('error', 'Kitap silinemedi: ' . $e->getMessage());
            $this->session->set_flashdata('message', 'Kitap silinirken bir hata oluştu: ' . $e->getMessage());
        }
        redirect('kitap');
    }

    public function get_book($id)
    {
        try {
            $veritabani = "kullanicilar";
            $koleksiyon = $this->mongodb->selectCollection($veritabani, 'kitaplar');

            $kitap = $koleksiyon->findOne(['_id' => new ObjectId($id)]);
            
            if ($kitap) {
                $response = [
                    '_id' => (string)$kitap->_id,
                    'book_title' => $kitap->book_title,
                    'year' => $kitap->year,
                    'price' => $kitap->price,
                    'category_code' => $kitap->category_code,
                    'publisher' => $kitap->publisher,
                    'writer' => $kitap->writer,
                    'stock' => $kitap->stock,
                    'book_img' => isset($kitap->book_img) ? $kitap->book_img : ''
                ];
                echo json_encode($response);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Kitap bulunamadı']);
            }
        } catch (Exception $e) {
            log_message('error', 'Kitap bulunamadı: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Kitap bulunamadı: ' . $e->getMessage()]);
        }
    }
}