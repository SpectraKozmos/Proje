# Darkness Kütüphane Projesi

Bu proje, modern bir kütüphane yönetim sistemi olarak üç kişilik bir ekip tarafından geliştirilmiştir. CodeIgniter framework'ü üzerine inşa edilen sistem, hem MySQL hem de MongoDB veritabanlarını kullanarak hibrit bir yapıda çalışmaktadır.

## Ekip ve Görev Dağılımı

### 1. Frontend & UI Geliştirici - `Semih Yavuz`
- Kullanıcı arayüzü tasarımı
- Bootstrap ve jQuery implementasyonu
- Responsive tasarım
- Form validasyonları
- AJAX entegrasyonları

### 2. Backend Geliştirici - `Muhammet Taşkıran`
- CodeIgniter framework kurulumu ve yapılandırması
- MySQL veritabanı tasarımı ve implementasyonu
- Temel CRUD operasyonları
- Session yönetimi
- Güvenlik önlemleri

### 3. Database & Integration Specialist - `Muhammed Vahit Elik`
- MongoDB entegrasyonu
- Veritabanı migrasyonları
- Kategori yönetimi (MongoDB)
- Kitap-Kategori ilişkileri
- Performans optimizasyonları

## Teknik Detaylar

### Kullanılan Teknolojiler
- PHP (CodeIgniter 3)
- MySQL
- MongoDB
- Bootstrap 4
- jQuery
- AJAX

### Veritabanı Yapısı
- MySQL: Kullanıcı yönetimi ve temel işlemler
- MongoDB: Kategori ve kitap yönetimi

### Özellikler
- Kullanıcı girişi ve yetkilendirme
- Kitap ekleme, düzenleme, silme
- Kategori yönetimi
- Resim yükleme
- Dinamik arama ve filtreleme

## Proje Geliştirme Süreci

1. **Planlama Aşaması**
   - Görev dağılımı
   - Veritabanı şeması tasarımı
   - API endpoint'lerinin belirlenmesi

2. **Geliştirme Aşaması**
   - Frontend geliştirme (Semih Yavuz)
   - Backend geliştirme (Muhammet Taşkıran)
   - Veritabanı entegrasyonu (Muhammed Vahit Elik)

3. **Test ve Optimizasyon**
   - Birim testleri
   - Entegrasyon testleri
   - Performans optimizasyonu

## Kurulum

1. XAMPP kurulumu
2. Proje dosyalarının `htdocs` klasörüne kopyalanması
3. MySQL veritabanının import edilmesi
4. MongoDB bağlantı ayarlarının yapılandırılması
5. Composer dependencies kurulumu

```bash
composer install
```

## Katkıda Bulunma
1. Fork'layın
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit'leyin (`git commit -m 'feat: Add some amazing feature'`)
4. Branch'e push yapın (`git push origin feature/amazing-feature`)
5. Pull Request açın

## Lisans
MIT License - Detaylar için `LICENSE` dosyasına bakın.
