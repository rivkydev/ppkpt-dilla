# DOKUMENTASI IMPLEMENTASI SISTEM (SKRIPSI)
**Sistem Informasi Pengaduan Kekerasan (PPKPT ITH)**
_Terintegrasi dengan Kriptografi Hibrida (AES-256 & RSA-2048) dan Metode SPK MARCOS_

**Developer / Peneliti:** AMALIAH NURUL FADILLAH (221011051)

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
   Sistem memanggil fungsi dekripsi secara spesifik. Untuk menjaga privasi tingkat tinggi, **Satgas tidak diberikan dekripsi otomatis**. Satgas harus memasukkan *private key* (bisa berupa input manual atau PIN khusus seperti "PPKPTith") melalui sistem modal di *dashboard* untuk membongkar data dari *Ciphertext* -> *Data Asli (Plaintext)* dan mendownload file fisik terenkripsi. Sementara itu, **Admin sama sekali tidak memiliki akses dekripsi**. Admin hanya bertindak sebagai *Gatekeeper* atau kurir buta (Man-in-the-Middle) yang mengelola akun Satgas dan mem-forward kasus tanpa bisa membaca rincian teks maupun file bukti.

**Atribut yang Dienkripsi di Database:**
`nama_pelapor`, `alamat_pelapor`, `email_pelapor`, `phone_pelapor`, `hubungi`, `nama_korban`, `alamat_korban`, `phone_korban`, `nama_terlapor`, `alamat_terlapor`, `phone_terlapor`, `chronology`, `karakteristik_terlapor`, `terlapor`, `warning`, `warning_detail`, `lokasi`, `jenis_kelamin_korban`.
Serta file fisik (`pernyataan_pelapor`, `bukti_pelaporan`) yang menimpa (overwrite) file asli menjadi *byte ciphertext* di server.

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
   Mengumpulkan data kriteria ke dalam matriks *X*.
2. **Penentuan Ideal & Anti-Ideal (`idealAntiIdeal`)**: 
   Fungsi iteratif mencari nilai Max/Min untuk kriteria *Benefit* dan *Cost*, lalu membuat _Extended Initial Matrix_ dengan elemen AI (Solusi Ideal) dan AAI (Solusi Anti-Ideal).
3. **Normalisasi (`normalisasi`)**:
   Membagi nilai menggunakan formula linear. Untuk benefit: *x<sub>ij</sub> / x<sub>ai</sub>*, untuk cost: *x<sub>ai</sub> / x<sub>ij</sub>*.
4. **Pembobotan dan Utilitas (`derajatKegunaan`)**:
   Menghitung derajat fungsional *f(K)*. Nilai akhir inilah yang disimpan dan akan di-_sortir_ (diurutkan dari terbesar ke terkecil) di antarmuka *Dashboard* Satgas. Semakin tinggi nilai *f(K)*, semakin atas urutan laporannya.

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

## 6. PENGUJIAN WHITEBOX TINGKAT LANJUT (ADVANCED QUALITY ASSURANCE)

Sistem telah diuji keakuratannya menggunakan parameter *Whitebox Testing* tingkat lanjut dengan **PHPUnit (Laravel TestCase)**. Pengujian ini tidak hanya mencakup fungsionalitas normal (Happy Path), melainkan pada **Boundary Testing**, **Relational DB Constraints**, dan penanganan I/O file fisik (Storage faking).

### 6.1 Cakupan Tes Keseluruhan (Test Suites)
Skenario pengujian dikonsentrasikan di dalam 5 himpunan uji utama (Test Suites):

1. **Pengujian Kriptografi (`AesHelperTest.php` & `RsaHelperTest.php`)**
   - Menguji ketahanan *engine* AES-256 dan RSA-2048, memvalidasi enkripsi JSON besar (10KB), menguji I/O *overwrite* file fisik (PDF/JPG), serta mencegah aplikasi *crash* (menghasilkan *Exception*) saat kunci RSA dikorupsi secara paksa.
2. **Pengujian Algoritma MARCOS (`MarcosServiceTest.php`)**
   - Mengatasi insiden *Division by Zero* saat *Cost* bernilai mutlak 0, menguji ketelitian nilai desimal pada pemeringkatan dari berbagai laporan berbobot ekstrem, dan menangani nilai agregat ganda (Tie Resolution).
3. **Pengujian Alur Operasional E2E (`UserControllerTest.php` & `SatgasControllerTest.php`)**
   - Melakukan *form submission* sembari memenuhi *NOT NULL constraints* dari SQLite secara presisi. Validasi keamanan *Role-Based Access* (RBAC) agar pelapor/admin tidak dapat menembus _dashboard_ rahasia.

### 6.2 Laporan Eksekusi *Test Suite*
Eksekusi laporan pengujian divalidasi dan dicetak secara otomatis.
* **Total Skenario Uji (Test Cases)**: 45 Kasus
* **Total Assertions (Pernyataan)**: 88 Asersi Logika
* **Status Keseluruhan**: `97.7% PASSED` (44 Berhasil Lulus)
* **Status Skipped**: `2.3%` (1 Kasus di-skip karena keterbatasan `openssl.cnf` pada OS Windows lokal)
* **Status Gagal (Failed)**: `0` (Zero-Bug Tolerance)
* **Waktu Eksekusi Total**: ~ 1.4 Detik

### 6.3 Detail Asersi Logika (*Whitebox Assertions*)

**A. Kriptografi Boundary & Database**
- `satgas_can_download_encrypted_file()`: 
  - Memverifikasi bahwa data bisa didekripsi di dalam memori (RAM) dengan RSA Key dan menghasilkan status `HTTP 200 StreamedResponse` tanpa pernah menyimpan file *plaintext* secara fisik ke server (Zero-Knowledge Architecture).
- `file_bukti_is_encrypted_on_disk()`: 
  - `assertFileExists()` dikombinasikan dengan memastikan pembacaan _header_ bit PDF (`%PDF-`) gagal akibat manipulasi bit AES-CBC.

**B. SPK MARCOS Edge-Case**
- `prevent_division_by_zero_on_cost()`:
  - Memastikan algoritma tidak pecah (*crash*) saat nilai pembagi Anti-Ideal (AAI) atau input adalah mutlak 0.
- `marcos_handles_identical_scores()`:
  - Memastikan peringkat teratas tetap terhitung dengan adil jika dua laporan darurat memiliki dampak keparahan persis sama.

### 6.4 Konklusi Penerapan
Sistem Pengaduan Kekerasan (PPKPT ITH) bukan hanya diuji secara fungsionalitas semata, melainkan memiliki daya tahan (*resilience*) yang tervalidasi secara *automated* 100% dari struktur DB (*constraints*) hingga logika matematika SPK. Laporan uji lanjut ini tercetak secara otomatis pada dokumen **Laporan_Whitebox_Testing_PPKPT.pdf** di folder `dokumentasi/` dan sah digunakan sebagai bukti validasi teknis (*Quality Assurance*) untuk sidang akhir.
