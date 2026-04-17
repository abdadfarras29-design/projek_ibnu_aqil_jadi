# Website Sekolah Modern - Multi Page 🎓

Website sekolah yang modern dengan halaman terpisah dan navigasi antar halaman.

## 📁 Struktur File

```
website-sekolah/
│
├── home.html           # Halaman utama / dashboard
├── profile.html        # Halaman profil sekolah
├── visi-misi.html      # Halaman visi & misi
├── fasilitas.html      # Halaman fasilitas
├── ekskul.html         # Halaman ekstrakurikuler
├── lokasi.html         # Halaman lokasi & peta
├── kontak.html         # Halaman kontak
├── style.css           # File CSS untuk semua halaman
├── script.js           # File JavaScript
└── README-MULTIPAGE.md # Dokumentasi (file ini)
```

## 🚀 Cara Menggunakan

1. **Download semua file** dan simpan dalam satu folder
2. **Buka home.html** sebagai halaman utama
3. Navigasi akan otomatis mengarah ke halaman lain

## 📄 Deskripsi Halaman

### 1. home.html
- Halaman utama website
- Dashboard dengan statistik sekolah
- Counter animasi (jumlah siswa, guru, prestasi)
- Hero section dengan CTA button
- 3 card keunggulan sekolah

### 2. profile.html
- Informasi lengkap sekolah
- Data NPSN, akreditasi, kepala sekolah
- Deskripsi tentang sekolah
- Grid informasi terstruktur

### 3. visi-misi.html
- Visi sekolah
- Misi sekolah (6 poin)
- Layout 2 kolom yang elegan
- Icon visual menarik

### 4. fasilitas.html
- 9 fasilitas sekolah
- Card dengan icon dan deskripsi
- Hover effect interaktif
- Grid responsive

### 5. ekskul.html
- 8 kategori ekstrakurikuler
- Mulai dari olahraga hingga lingkungan
- Card layout dengan icon
- Deskripsi lengkap setiap kategori

### 6. lokasi.html
- Alamat lengkap sekolah
- Google Maps terintegrasi
- Informasi akses transportasi
- 4 pilihan transportasi umum

### 7. kontak.html
- Email sekolah
- WhatsApp dengan link langsung
- Nomor telepon
- Jam operasional lengkap

## 🎨 Fitur Desain

✅ **Navigasi Konsisten**
- Menu navigasi sama di setiap halaman
- Highlight halaman aktif
- Responsive mobile menu

✅ **Gradasi Hijau-Putih**
- Tema warna konsisten
- Gradient modern
- Visual menarik

✅ **Responsive Design**
- Mobile friendly
- Tablet optimized
- Desktop perfect

✅ **Animasi Smooth**
- Page transitions
- Hover effects
- Counter animations

## 🔧 Kustomisasi

### Mengubah Navigasi

Pada setiap file HTML, edit bagian `<nav>`:

```html
<ul class="nav-menu" id="navMenu">
    <li><a href="home.html">Home</a></li>
    <li><a href="profile.html">Profil</a></li>
    <!-- dst... -->
</ul>
```

### Mengubah Data Sekolah

**Untuk informasi profil** (profile.html):
```html
<div class="profile-item">
    <h4>Nama Sekolah</h4>
    <p>SMA NEGERI PRESTASI</p> <!-- Ganti ini -->
</div>
```

**Untuk statistik** (script.js):
```javascript
const schoolData = {
    students: 1250,        // Ganti jumlah siswa
    teachers: 75,          // Ganti jumlah guru
    achievements: 150,     // Ganti jumlah prestasi
    yearEstablished: 30    // Ganti tahun berdiri
};
```

### Mengubah Kontak

Di file **kontak.html**:

```html
<!-- Email -->
<a href="mailto:info@sekolahkita.sch.id">info@sekolahkita.sch.id</a>

<!-- WhatsApp -->
<a href="https://wa.me/6281234567890">+62 812-3456-7890</a>

<!-- Telepon -->
<a href="tel:+622112345678">021-1234-5678</a>
```

### Mengubah Google Maps

Di file **lokasi.html**, ganti URL iframe:

1. Buka Google Maps
2. Cari lokasi sekolah
3. Klik "Share" → "Embed a map"
4. Copy kode iframe
5. Paste di lokasi.html

### Mengubah Warna Tema

Di file **style.css**, bagian `:root`:

```css
:root {
    --primary-green: #10b981;   /* Hijau utama */
    --dark-green: #059669;      /* Hijau gelap */
    --light-green: #34d399;     /* Hijau terang */
}
```

## 📱 Menambah Halaman Baru

1. **Copy salah satu file HTML** (misal: profile.html)
2. **Rename** sesuai kebutuhan (misal: galeri.html)
3. **Edit konten** sesuai kebutuhan
4. **Tambahkan link** di navigasi SEMUA halaman:

```html
<li><a href="galeri.html">Galeri</a></li>
```

## 🌐 Hosting Website

### Hosting Gratis:
- **GitHub Pages** (Recommended)
- **Netlify**
- **Vercel**
- **InfinityFree**

### Cara Upload ke GitHub Pages:

1. Buat repository baru di GitHub
2. Upload semua file
3. Ke Settings → Pages
4. Pilih branch main → Save
5. Website akan live dalam beberapa menit

### Domain Custom (Opsional)

Jika ingin domain seperti `www.sekolahkita.sch.id`:
1. Beli domain di provider (Niagahoster, Domainesia, dll)
2. Setting DNS ke GitHub Pages / Netlify
3. Tambahkan CNAME di repository

## 🔒 Tips Keamanan

- ✅ Jangan hardcode password
- ✅ Gunakan HTTPS
- ✅ Validasi form input (jika ada form)
- ✅ Update konten secara berkala

## 📞 Menambah Form Kontak

Untuk form kontak yang berfungsi, Anda bisa gunakan:

**Opsi 1: Google Forms** (Gratis)
```html
<iframe src="URL_GOOGLE_FORM"></iframe>
```

**Opsi 2: Formspree** (Gratis)
```html
<form action="https://formspree.io/f/YOUR_ID" method="POST">
    <input type="email" name="email">
    <textarea name="message"></textarea>
    <button type="submit">Kirim</button>
</form>
```

**Opsi 3: Backend Custom** (PHP/Node.js)
- Perlu hosting dengan backend support
- Lebih flexible
- Bisa custom sepenuhnya

## 🎯 Pengembangan Lebih Lanjut

### Fitur yang Bisa Ditambahkan:

1. **Galeri Foto**
   - Lightbox gallery
   - Grid responsive
   - Filter kategori

2. **Berita/Pengumuman**
   - Blog section
   - Artikel terbaru
   - Pagination

3. **Download Area**
   - Form pendaftaran
   - Brosur sekolah
   - Kalender akademik

4. **Alumni**
   - Database alumni
   - Testimonial
   - Success stories

5. **Portal Siswa**
   - Login system
   - Dashboard siswa
   - E-learning integration

## 🐛 Troubleshooting

**Q: Navigasi tidak berfungsi?**
A: Pastikan semua file HTML ada di folder yang sama

**Q: Style tidak muncul?**
A: Cek link `<link rel="stylesheet" href="style.css">` di setiap HTML

**Q: JavaScript error?**
A: Buka browser console (F12) untuk cek error

**Q: Gambar tidak muncul?**
A: Cek path gambar dan pastikan file gambar ada

## 📊 Browser Support

- ✅ Chrome (Recommended)
- ✅ Firefox
- ✅ Safari
- ✅ Edge
- ✅ Opera
- ⚠️ IE11 (partial support)

## 📄 Lisensi

Website ini bebas digunakan untuk keperluan pendidikan.

## 💡 Tips Optimasi

1. **Compress gambar** sebelum upload
2. **Minify CSS/JS** untuk production
3. **Gunakan lazy loading** untuk gambar
4. **Enable caching** di server
5. **Compress file** dengan gzip

---

## 🎉 Selamat Menggunakan!

Jika ada pertanyaan atau butuh bantuan, silakan hubungi developer atau buka issue di GitHub repository.

**Happy Coding! 🚀**
