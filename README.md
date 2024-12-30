# Darkness Kütüphane Projesi

Bu proje, modern bir kütüphane yönetim sistemi olarak üç kişilik bir ekip tarafından geliştirilmiştir. CodeIgniter framework'ü üzerine inşa edilen sistem, hem MySQL hem de MongoDB veritabanlarını kullanarak hibrit bir yapıda çalışmaktadır.

## Ekip ve Görev Dağılımı

### 1. Database & Migration Specialist - Semih Yavuz
- Veritabanı migrasyonları
- Kategori yönetimi (MongoDB)
- Kitap-Kategori ilişkileri
- Performans optimizasyonları
- Session yönetimi

### 2. Frontend & UI Specialist - Muhammet Taşkıran
- Kullanıcı arayüzü tasarımı
- Bootstrap ve jQuery implementasyonu
- Responsive tasarım
- MySQL veritabanı tasarımı ve implementasyonu
- AJAX entegrasyonları
- Güvenlik önlemleri

### 3. Backend & Integration Developer - Muhammed Vahit Elik
- MongoDB entegrasyonu
- CodeIgniter framework kurulumu ve yapılandırması
- Form validasyonları
- Temel CRUD operasyonları

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
   - Frontend geliştirme (Muhammet Taşkıran)
   - Backend geliştirme (Muhammed Vahit Elik)
   - Veritabanı entegrasyonu (Semih Yavuz)

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
