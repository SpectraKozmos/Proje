const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');

const app = express();
const PORT = process.env.PORT || 3000;

// MongoDB bağlantısı
mongoose.connect('mongodb+srv://admin:1234@cluster0.ay1xf.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0', { useNewUrlParser: true, useUnifiedTopology: true })
    .then(() => console.log('MongoDB bağlantısı başarılı!'))
    .catch(err => console.error('MongoDB bağlantısı hatası:', err));

// Şikayet şeması
const complaintSchema = new mongoose.Schema({
    name: String,
    email: String,
    complaint: String,
    createdAt: { type: Date, default: Date.now }
});

const Complaint = mongoose.model('Complaint', complaintSchema);

// Middleware
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Statik dosyaları sunmak için middleware ekleyin
app.use(express.static('c:/xampp/htdocs/Proje-main'));

// Şikayet gönderme endpoint'i
app.post('/api/complaints', async (req, res) => {
    const { name, email, complaint } = req.body;
    const newComplaint = new Complaint({ name, email, complaint });
    try {
        await newComplaint.save();
        res.status(201).send('Şikayet başarıyla gönderildi.');
    } catch (error) {
        res.status(500).send('Şikayet gönderilirken bir hata oluştu.');
    }
});

// Şikayetleri çekme endpoint'i
app.get('/api/complaints', async (req, res) => {
    try {
        const complaints = await Complaint.find();
        res.status(200).json(complaints);
    } catch (error) {
        res.status(500).send('Şikayetler çekilirken bir hata oluştu.');
    }
});

// Ana sayfa yönlendirmesi
app.get('/', (req, res) => {
    res.sendFile('c:/xampp/htdocs/Proje-main/complaint.html');
});

app.listen(PORT, () => {
    console.log(`Server ${PORT} portunda çalışıyor.`);
});
