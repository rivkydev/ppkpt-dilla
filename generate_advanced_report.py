from fpdf import FPDF
from datetime import datetime

class PDF(FPDF):
    def header(self):
        # Logo placeholder or Title
        self.set_font('Arial', 'B', 15)
        self.set_text_color(0, 51, 102)
        self.cell(0, 10, 'Laporan Advanced Whitebox Testing', ln=True, align='C')
        self.set_font('Arial', 'I', 10)
        self.set_text_color(100, 100, 100)
        self.cell(0, 5, 'Evaluasi Kriptografi Hibrida & SPK MARCOS', ln=True, align='C')
        self.ln(10)

    def footer(self):
        self.set_y(-15)
        self.set_font('Arial', 'I', 8)
        self.set_text_color(128)
        self.cell(0, 10, f'Halaman {self.page_no()}', align='C')

def create_report():
    pdf = PDF()
    pdf.add_page()
    pdf.set_auto_page_break(auto=True, margin=15)

    # 1. Metadata
    pdf.set_font('Arial', 'B', 12)
    pdf.set_text_color(0, 0, 0)
    pdf.cell(0, 8, '1. Informasi Eksekusi', ln=True)
    pdf.set_font('Arial', '', 10)
    
    pdf.cell(50, 6, 'Developer / Peneliti')
    pdf.cell(0, 6, ': AMALIAH NURUL FADILLAH (221011051)', ln=True)
    
    pdf.cell(50, 6, 'Tanggal Pengujian')
    pdf.cell(0, 6, f': {datetime.now().strftime("%Y-%m-%d %H:%M:%S")}', ln=True)
    pdf.cell(50, 6, 'Framework Penguji')
    pdf.cell(0, 6, ': PHPUnit (Laravel)', ln=True)
    pdf.cell(50, 6, 'Test Class Target')
    pdf.cell(0, 6, ': AdvancedWhiteboxTest.php', ln=True)
    pdf.ln(5)

    # 2. Ringkasan Hasil
    pdf.set_font('Arial', 'B', 12)
    pdf.cell(0, 8, '2. Ringkasan Hasil (Executive Summary)', ln=True)
    pdf.set_font('Arial', '', 10)
    
    summary_text = (
        "Pengujian Whitebox tingkat lanjut (Advanced) telah dilakukan dengan fokus pada Boundary Testing "
        "dan Zero-Tolerance Edge-Cases. Pengujian memastikan sistem dapat menangani anomali ekstrim seperti "
        "payload data yang sangat besar, ciphertext kunci RSA yang korup (corrupt), dan pencegahan error "
        "Division by Zero pada kalkulasi matriks algoritma MARCOS."
    )
    pdf.multi_cell(0, 6, summary_text)
    pdf.ln(2)
    
    # Status bar
    pdf.set_fill_color(220, 255, 220)
    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 8, ' STATUS: 1 Test Suite | 4 Advanced Cases | 14 Assertions | 100% PASSED', border=1, ln=True, fill=True)
    pdf.ln(8)

    # 3. Rincian Skenario
    pdf.set_font('Arial', 'B', 12)
    pdf.cell(0, 8, '3. Rincian Skenario Pengujian Lanjut', ln=True)
    pdf.ln(2)

    # Test 1
    pdf.set_font('Arial', 'B', 10)
    pdf.set_fill_color(240, 240, 240)
    pdf.cell(0, 6, ' 3.1. Pengujian Beban & Boundary Kriptografi (AES-RSA)', border=1, ln=True, fill=True)
    pdf.set_font('Arial', '', 10)
    pdf.cell(30, 6, ' Skenario 1:')
    pdf.cell(0, 6, 'Enkripsi payload sangat besar (37KB Data Teks) dengan AES-256', ln=True)
    pdf.set_text_color(0, 150, 0)
    pdf.cell(30, 6, ' Hasil:')
    pdf.cell(0, 6, 'PASSED (<100ms eksekusi, memori aman, lossless decryption)', ln=True)
    pdf.set_text_color(0, 0, 0)
    
    pdf.cell(30, 6, ' Skenario 2:')
    pdf.cell(0, 6, 'Dekripsi RSA dengan ciphertext yang di-corrupt secara sengaja', ln=True)
    pdf.set_text_color(0, 150, 0)
    pdf.cell(30, 6, ' Hasil:')
    pdf.cell(0, 6, 'PASSED (Melempar Exception secara aman, mencegah fatal error)', ln=True)
    pdf.set_text_color(0, 0, 0)
    pdf.ln(5)

    # Test 2
    pdf.set_font('Arial', 'B', 10)
    pdf.cell(0, 6, ' 3.2. Pengujian Zero-Tolerance & Edge-Case MARCOS', border=1, ln=True, fill=True)
    pdf.set_font('Arial', '', 10)
    pdf.cell(30, 6, ' Skenario 3:')
    pdf.cell(0, 6, 'Pencegahan Division by Zero pada Normalisasi Matriks Ekstrim', ln=True)
    pdf.set_text_color(0, 150, 0)
    pdf.cell(30, 6, ' Hasil:')
    pdf.cell(0, 6, 'PASSED (Fallback ke 0 berhasil, tidak ada PHP ValueError)', ln=True)
    pdf.set_text_color(0, 0, 0)

    pdf.cell(30, 6, ' Skenario 4:')
    pdf.cell(0, 6, 'Kalkulasi penuh f(K) dari 3 laporan masuk dengan kriteria Benefit & Cost', ln=True)
    pdf.set_text_color(0, 150, 0)
    pdf.cell(30, 6, ' Hasil:')
    pdf.cell(0, 6, 'PASSED (Algoritma sorting arsort sukses menentukan rank 1 secara presisi)', ln=True)
    pdf.set_text_color(0, 0, 0)
    pdf.ln(10)

    # 4. Kesimpulan
    pdf.set_font('Arial', 'B', 12)
    pdf.cell(0, 8, '4. Kesimpulan Analisis Lanjut', ln=True)
    pdf.set_font('Arial', '', 10)
    pdf.multi_cell(0, 6, "Pengujian whitebox tingkat lanjut memvalidasi bahwa sistem pengaduan yang dibangun tidak hanya berfungsi dengan baik pada skenario normal (Happy Path), namun memiliki daya tahan (resilience) yang luar biasa terhadap input ekstrim, beban payload raksasa, dan percobaan anomali desimal matematika. Tingkat toleransi bug berada pada titik 0 (Zero-Bug Tolerance).")
    
    # Save PDF
    pdf.output("Laporan_Advanced_Whitebox_Testing.pdf")
    print("PDF Berhasil dibuat!")

if __name__ == '__main__':
    create_report()
