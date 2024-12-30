<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_mongodb_connection')) {
    function get_mongodb_connection() {
        static $client = null;
        
        if ($client === null) {
            require_once APPPATH . '../vendor/autoload.php';
            
            $kul_adi = "admin";
            $sifre = "1234";
            $adres = "cluster0.ay1xf.mongodb.net";
            
            $uri = "mongodb+srv://{$kul_adi}:{$sifre}@{$adres}/?retryWrites=true&w=majority";
            
            try {
                $client = new MongoDB\Client($uri);
                // Test connection
                $client->listDatabases();
            } catch (Exception $e) {
                log_message('error', 'MongoDB Bağlantı Hatası: ' . $e->getMessage());
                show_error('Veritabanı bağlantı hatası: ' . $e->getMessage());
            }
        }
        
        return $client;
    }
}
