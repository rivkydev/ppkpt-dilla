from fpdf import FPDF

class PDF(FPDF):
    def footer(self):
        self.set_y(-15)
        self.set_font('Arial', 'I', 8)
        self.set_text_color(128)
        self.cell(0, 10, f'Halaman {self.page_no()}', align='C')

def create_revisi_pdf():
    pdf = PDF()
    pdf.add_page()
    pdf.set_auto_page_break(auto=True, margin=15)
    
    # Title (Only on first page)
    pdf.set_font('Arial', 'B', 14)
    pdf.set_text_color(0, 51, 102)
    pdf.cell(0, 10, 'Usulan Revisi Naskah Skripsi (BAB I - III)', ln=True, align='C')
    pdf.set_font('Arial', 'I', 10)
    pdf.set_text_color(100, 100, 100)
    pdf.cell(0, 5, 'Integrasi Algoritma MARCOS pada Sistem Pengaduan', ln=True, align='C')
    pdf.ln(10)
    
    # Enable Unicode characters (use default latin-1, but replace non-latin if needed)
    
    # BAB 1
    pdf.set_font('Arial', 'B', 12)
    pdf.set_text_color(0, 0, 0)
    pdf.cell(0, 8, 'BAB I: PENDAHULUAN', ln=True)
    
    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, '1. Penambahan pada 1.1 Latar Belakang', ln=True)
    pdf.set_font('Arial', 'I', 9)
    pdf.cell(0, 5, '(Selipkan tepat sebelum paragraf terakhir di latar belakang)', ln=True)
    pdf.set_font('Arial', '', 10)
    teks1 = (
        "Selain permasalahan keamanan data, tantangan operasional lain yang kerap dihadapi dalam pengelolaan "
        "sistem pengaduan mahasiswa adalah proses penentuan prioritas penanganan kasus. Terkadang, kuantitas "
        "pengaduan yang masuk cukup tinggi, menyulitkan pihak berwenang memilah laporan yang memiliki tingkat urgensi tertinggi. "
        "Oleh karena itu, diperlukan sebuah Sistem Pendukung Keputusan (SPK) terintegrasi yang mampu melakukan komputasi perankingan "
        "urgensi pengaduan. Salah satu metode pengambilan keputusan multikriteria yang memiliki tingkat presisi dan stabilitas "
        "tinggi adalah algoritma MARCOS (Measurement of Alternatives and Ranking according to COmpromise Solution). "
        "Penggunaan metode ini dinilai sangat efektif untuk membantu Satgas secara otomatis mendeteksi dan menangani kasus "
        "yang berbobot paling darurat terlebih dahulu berdasarkan kriteria-kriteria yang terukur."
    )
    pdf.multi_cell(0, 5, teks1)
    pdf.ln(5)

    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, '2. Penambahan pada 1.2 Rumusan Masalah (Poin 3)', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 5, "3. Bagaimana mengimplementasikan metode Sistem Pendukung Keputusan (SPK) MARCOS untuk menentukan tingkat prioritas penanganan pengaduan mahasiswa secara otomatis?")
    pdf.ln(5)

    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, '3. Penambahan pada 1.3 Batasan Masalah', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 5, "- Penentuan prioritas penanganan pengaduan dibatasi menggunakan algoritma SPK MARCOS berdasarkan kriteria bobot dampak kasus, potensi bahaya, tingkat kerugian, dan ketersediaan bukti.")
    pdf.ln(5)

    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, '4. Penambahan pada 1.4 Tujuan Penelitian (Poin 3)', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 5, "3. Mengimplementasikan metode Sistem Pendukung Keputusan (SPK) MARCOS guna menghasilkan kalkulasi dan perankingan prioritas penanganan pengaduan secara terkomputerisasi.")
    pdf.ln(10)

    # BAB 2
    pdf.set_font('Arial', 'B', 12)
    pdf.cell(0, 8, 'BAB II: TINJAUAN PUSTAKA', ln=True)
    
    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, '1. Penambahan pada Tabel 2.1 Keaslian Penelitian (Kebaruan)', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 5, "Penelitian ini tidak hanya berfokus pada pengamanan data menggunakan Hybrid Encryption AES-256 dan RSA-2048, melainkan turut mengintegrasikan SPK menggunakan algoritma MARCOS untuk menganalisis dan merangking urgensi penanganan dari data pengaduan yang telah didekripsi secara lossless.")
    pdf.ln(5)

    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, '2. Penambahan pada 2.2 Dasar Teori (Tambahkan 2 Sub-Bab Baru)', ln=True)
    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 5, 'Sistem Pendukung Keputusan (SPK)', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 5, "SPK merupakan sistem informasi berbasis komputer yang dirancang untuk membantu pengambil keputusan dengan memanfaatkan data dan model matematika tertentu guna memecahkan masalah semi-terstruktur maupun tidak terstruktur.")
    pdf.ln(2)
    
    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 5, 'Algoritma MARCOS', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 5, "MARCOS (Measurement of Alternatives and Ranking according to COmpromise Solution) merupakan metode MCDM yang didasarkan pada pendefinisian hubungan antara alternatif dengan solusi ideal (AI) dan solusi anti-ideal (AAI). Keunggulan MARCOS terletak pada kemampuannya mengukur fungsi utilitas alternatif secara lebih stabil dan presisi terhadap sekumpulan kriteria yang sifatnya saling bertolak belakang.")
    pdf.ln(10)

    # BAB 3
    pdf.add_page()
    pdf.set_font('Arial', 'B', 12)
    pdf.cell(0, 8, 'BAB III: METODE PENELITIAN', ln=True)
    
    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, '1. Penambahan pada 3.3.1 Arsitektur Sistem', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 5, "Setelah proses dekripsi berhasil dilakukan, sistem akan secara otomatis mengekstraksi nilai kriteria dari laporan tersebut dan mengirimkannya ke dalam MARCOS Engine. Modul ini akan menghitung normalisasi matriks, bobot kriteria, serta menghitung fungsi utilitas untuk kemudian menyajikan dashboard perankingan aduan.")
    pdf.ln(5)

    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, '3. Penambahan pada 3.6 Metode Pengujian Sistem', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 5, "Fokus pengujian diarahkan pada alur internal sistem, mulai dari pemanggilan fungsi enkripsi, penyimpanan data terenkripsi, akurasi proses dekripsi (lossless decryption), hingga validasi komputasi perhitungan desimal matematika pada algoritma MARCOS menggunakan Whitebox Testing (PHPUnit).")
    
    # Save PDF
    pdf.output("Usulan_Revisi_Skripsi_MARCOS.pdf")
    print("PDF Usulan Revisi berhasil dibuat!")

if __name__ == '__main__':
    create_revisi_pdf()
