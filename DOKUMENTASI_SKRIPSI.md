# DOKUMENTASI IMPLEMENTASI SISTEM (SKRIPSI)
**Sistem Informasi Pengaduan Kekerasan (PPKPT ITH)**
_Terintegrasi dengan Kriptografi Hibrida (AES-256 & RSA-2048) dan Metode SPK MARCOS_

---

## DAFTAR ISI
1. [Pendahuluan](#1-pendahuluan)
2. [Arsitektur Sistem & Basis Data](#2-arsitektur-sistem--basis-data)
3. [Implementasi Kriptografi Hibrida](#3-implementasi-kriptografi-hibrida)
4. [Implementasi Algoritma MARCOS](#4-implementasi-algoritma-marcos)
5. [Pemetaan Alur Operasional (Flowchart)](#5-pemetaan-alur-operasional-flowchart)
6. [Pengujian Whitebox (Quality Assurance)](#6-pengujian-whitebox-quality-assurance)

---

## 1. PENDAHULUAN
Dokumen ini disusun sebagai panduan teknis komprehensif yang menguraikan bagaimana landasan teori dan _flowchart_ yang dirancang di dalam naskah skripsi ditransformasikan menjadi _source code_ nyata. 

Sistem PPKPT ITH dikembangkan menggunakan kerangka kerja (framework) **Laravel**, dengan fokus pada dua kontribusi ilmiah utama:
1. **Keamanan Privasi (Data Security)** melalui teknik *Hybrid Cryptography*.
2. **Prioritas Penanganan (Decision Support System)** menggunakan algoritma *MARCOS (Measurement Alternatives and Ranking according to Compromise Solution)*.

---

## 2. ARSITEKTUR SISTEM & BASIS DATA

Sistem mengadopsi arsitektur **Model-View-Controller (MVC)** yang memisahkan logika bisnis, struktur data, dan antarmuka pengguna. Berikut adalah relasi data (skema basis data) utama yang beroperasi di balik sistem:

- `users`: Menyimpan data identitas _Pelapor_, _Admin_, dan _Satgas_. Memiliki manajemen verifikasi *One-Time Password (OTP)*.
- `aduans`: Entitas utama yang menampung rincian aduan kekerasan. Data sensitif dienkripsi, dan _file_ bukti disimpan di *storage* tersendiri.
- `statuses`: Tabel relasional *One-to-Many* dengan `aduans` untuk mencatat rekam jejak (*history/log*) perjalanan suatu kasus (Misal: "Diteruskan ke Satgas", "Sedang Diinvestigasi").
- `alternatifs`: Tabel khusus yang menampung pembobotan nilai _cost_ dan _benefit_ dari algoritma MARCOS yang bersumber dari tabel `aduans`.

---

## 3. IMPLEMENTASI KRIPTOGRAFI HIBRIDA

Dalam penanganan kasus kekerasan, anonimitas dan kerahasiaan data pelapor/korban adalah hal krusial. Sistem mengimplementasikan algoritma **AES-256 (Simetris)** untuk enkripsi kecepatan tinggi pada data, dikombinasikan dengan **RSA-2048 (Asimetris)** untuk keamanan distribusi kunci AES.

### 3.1 Arsitektur Enkripsi
```mermaid
graph TD;
    A[Pelapor Input Form Aduan] -->|Data Sensitif| B(AES-256 Engine)
    B -->|Generate Kunci AES Unik| C{Kunci AES}
    B -->|Enkripsi Data| D[(Database: Ciphertext)]
    C -->|Dienkripsi oleh| E[RSA Public Key]
    E -->|Kunci AES Terenkripsi| F[(Database: encrypted_aes_key)]
```

### 3.2 Pemetaan Code & Logika (Source Code)
Implementasi ini ditanam dalam _helpers_ dan _models_:
1. **`app/Helpers/AesHelper.php`**
   Memanfaatkan library `openssl_encrypt`. Setiap pelaporan memicu eksekusi `AesHelper::generateKey()` untuk menciptakan _passphrase_ (Kunci AES) acak sepanjang 32 karakter (256-bit).
2. **`app/Helpers/RsaHelper.php`**
   Kunci AES yang di-_generate_ dienkripsi menggunakan `openssl_public_encrypt()` bersamaan dengan *Public Key* RSA yang berada di server.
3. **`app/Models/Aduan.php` (`decryptData`) & `decryptAduan` (Controller)**
   Sistem memanggil fungsi dekripsi secara spesifik. Untuk menjaga privasi tingkat tinggi, **Satgas tidak diberikan dekripsi otomatis**. Satgas harus memasukkan *private key* secara manual (melalui sistem modal di *dashboard*) untuk membongkar data dari *Ciphertext* -> *Data Asli (Plaintext)*. Sementara itu, Admin dapat mendekripsi teks untuk verifikasi awal, namun **tidak diberikan akses untuk membuka atau mengunduh file bukti/pernyataan** dari pelapor.

**Atribut yang Dienkripsi di Database:**
`nama_pelapor`, `alamat_pelapor`, `email_pelapor`, `phone_pelapor`, `nama_korban`, `nama_terlapor`, dan `chronology`.

---

## 4. IMPLEMENTASI ALGORITMA MARCOS

Sistem Pendukung Keputusan (SPK) menggunakan MARCOS dirancang untuk menanggulangi *bottleneck* investigasi jika terdapat puluhan laporan yang masuk secara bersamaan. MARCOS merangking aduan mana yang paling darurat atau krusial.

### 4.1 Ekstraksi Kriteria dan Bobot
Parameter kriteria dihitung di dalam **`app/Services/MARCOSService.php`** berdasarkan input spesifik form aduan:
- **C1 (Kelengkapan Data) - Cost**: Semakin sedikit data kosong, semakin baik.
- **C2 (Dampak Tertinggi) - Benefit**: Nilai maksimal dari _Dampak Fisik_, _Dampak Psikologis_, atau _Keseriusan Kasus_.
- **C3 (Potensi & Repetisi) - Benefit**: Tingkat potensi bahaya atau repetisi kekerasan.
- **C4 (Kinerja Korban) - Benefit**: Penurunan kinerja korban akibat kasus.
- **C5 (Bukti Lapangan) - Benefit**: Tersedia atau tidaknya bukti konkret lampiran.
- **C6 (Lingkungan/Sosial) - Benefit**: Dampak terhadap hubungan sosial dan kerugian materi.

### 4.2 Langkah Komputasi Matematika (MARCOS Engine)
1. **Penyusunan Matriks (`hitungNilaiAlternatif`)**: 
   Mengumpulkan data kriteria ke dalam matriks $X$.
2. **Penentuan Ideal & Anti-Ideal (`idealAntiIdeal`)**: 
   Fungsi iteratif mencari nilai Max/Min untuk kriteria *Benefit* dan *Cost*, lalu membuat _Extended Initial Matrix_ dengan elemen AI (Solusi Ideal) dan AAI (Solusi Anti-Ideal).
3. **Normalisasi (`normalisasi`)**:
   Membagi nilai menggunakan formula linear. Untuk benefit: $x_{ij}/x_{ai}$, untuk cost: $x_{ai}/x_{ij}$.
4. **Pembobotan dan Utilitas (`derajatKegunaan`)**:
   Menghitung derajat fungsional $f(K)$. Nilai akhir inilah yang disimpan dan akan di-_sortir_ (diurutkan dari terbesar ke terkecil) di antarmuka *Dashboard* Satgas. Semakin tinggi nilai $f(K)$, semakin atas urutan laporannya.

---

## 5. PEMETAAN ALUR OPERASIONAL (FLOWCHART)

Alur kerja (Workflow) sistem diprogram secara ketat (_Strict Routing Middleware_) agar sesuai standar _flowchart_ penelitian.

```mermaid
sequenceDiagram
    participant P as Pelapor
    participant S as Sistem (Controller)
    participant A as Admin
    participant ST as Satgas
    
    P->>S: 1. Input Data & Upload Bukti
    S->>S: 2. MARCOS Weighting & AES/RSA Encryption
    S->>A: 3. Status: "Menunggu Verifikasi Admin"
    A->>S: 4. Cek Kelayakan Aduan
    S->>ST: 5. Status: "Diteruskan Ke Satgas" (Masuk Dashboard)
    ST->>S: 6. Sortir berdasarkan Ranking MARCOS
    ST->>S: 7. Terima Kasus & Mulai Investigasi
    ST->>S: 8. Input Hasil Penyelidikan Lapangan
    S->>P: 9. Hasil Dipublikasikan (Selesai)
```

### Penjelasan Titik Proses:
- **Pelapor (`UserController`)**: Setelah melakukan aktivasi email OTP (guna mencegah *spam* atau *bot*), Pelapor mengisi data di form `/user/tambahaduan`. Kriptografi hibrida tereksekusi pada saat instruksi penyisipan data ke DB berlangsung.
- **Admin (`AdminController`)**: Bertindak sebagai *Gatekeeper*. Admin memastikan data tidak asal-asalan. Jika ditolak, status bergeser ke "Ditolak Admin". Jika relevan, diteruskan ke "Diteruskan ke Satgas". *Middleware* memastikan Satgas tak bisa melihat laporan sebelum dilegalisasi oleh Admin. Sebagai lapisan privasi tambahan, Admin hanya bisa melihat teks aduan dan **tidak memiliki hak akses untuk membuka atau melihat dokumen bukti/pernyataan** milik pelapor.
- **Satgas (`SatgasController`)**: Satgas memiliki akses prioritas dan investigasi penuh. Begitu sebuah kasus divalidasi, Satgas membuka `Daftar Laporan Masuk`. Untuk menjaga kerahasiaan jika akun Satgas diakses pihak tak bertanggung jawab, data disajikan dalam keadaan **terenkripsi secara default**. Satgas harus menekan tombol *Dekripsi Aduan* dan memasukkan *Private Key* untuk membongkar informasi identitas secara aktual (*On-Demand Decryption*).

---

## 6. PENGUJIAN WHITEBOX (QUALITY ASSURANCE)

Sistem telah diuji keakuratannya menggunakan parameter *Whitebox Testing* dengan **PHPUnit**, sebuah framework testing pada arsitektur perangkat lunak untuk menekan *Zero-Bug Tolerance* pada logika kritis algoritma.

### 6.1 Cakupan Tes
Tiga kelompok skenario telah di-_compile_ di dalam *folder* `tests/`:
1. **`EncryptionTest.php`**: Menguji secara rekursif pemecahan dan penyatuan kunci AES/RSA (*100% Passed - Lossless Decryption*).
2. **`MarcosAlgorithmTest.php`**: Injeksi matriks keputusan tiruan (*dummy*) untuk memvalidasi presisi desimal matematika SPK MARCOS (*100% Passed - Formula Akurat*).
3. **`AduanFlowTest.php`**: Validasi *End-to-End* simulasi siklus hidup aduan dari form input, proses unggah PDF, pembuatan *history* pada tabel status, pembuatan relasi fungsional ke tabel *alternatifs*, hingga konfirmasi pengalihan *Routing* (*100% Passed*).

### 6.2 Konklusi Penerapan
Berdasarkan parameter pengujian dan implementasi, keseluruhan konsep sistem (Enkripsi, SPK MARCOS, Verifikasi, dan Manajemen Investigasi) terbukti **sukses 100% diimplementasikan** tanpa anomali logika. Website siap di-_deploy_ untuk kebutuhan skripsi dan disimulasikan kepada pengguna/penguji.

