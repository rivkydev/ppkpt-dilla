import os
import re

file_path = r'c:\Users\Hujan Deras\Downloads\PPKPT-master\PPKPT-master\resources\views\satgas\detaillaporan.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Add csrf token
content = content.replace('<head>', '<head>\n    <meta name="csrf-token" content="{{ csrf_token() }}">')

# Add alpine data to body
content = content.replace('<body>', '<body x-data="satgasDecryptPage({{ $aduan->id }})">')

# Add modal and button
modal_html = '''
                    <div class="mb-6">
                        <button
                            @click="showModal = true"
                            class="bg-blue-500 text-white px-4 py-2 rounded mb-2">
                            Dekripsi Aduan
                        </button>
                        <p class="text-sm text-gray-600" x-show="executionTime">
                            ⏱️ Waktu proses dekripsi: <span x-text="executionTime.toFixed(4)"></span> detik
                        </p>

                        <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
                            <div class="bg-white p-6 rounded w-96">
                                <h2 class="text-lg font-bold mb-4">Masukkan Key Dekripsi</h2>
                                <input type="password" x-model="key" class="w-full border px-3 py-2 rounded mb-4" placeholder="Masukkan private key" @keyup.enter="decryptAduan()">
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
                                    <button @click="decryptAduan()" class="px-4 py-2 bg-green-500 text-white rounded">Dekripsi</button>
                                </div>
                            </div>
                        </div>
                    </div>
'''
content = content.replace('<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">', modal_html + '\n                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">', 1)

# Replace all encrypted fields with Alpine tags
fields = ['nama_pelapor', 'alamat_pelapor', 'email_pelapor', 'phone_pelapor', 'hubungi', 'nama_korban', 'jenis_kelamin_korban', 'alamat_korban', 'phone_korban', 'status_korban', 'nama_terlapor', 'jenis_kelamin_terlapor', 'alamat_terlapor', 'phone_terlapor', 'status_terlapor', 'karakteristik_terlapor', 'chronology']

for field in fields:
    # Match {{ $aduan->FIELD ?? '-'}} or {{ $aduan->FIELD ?? '-' }}
    pattern = r'\{\{\s*\$aduan->' + field + r'\s*\?\?\s*\'-\'\s*\}\}'
    replacement = f'<span x-show="!isDecrypted">{{{{ $aduan->{field} ?? \'-\' }}}}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.{field} : \'-\'"></span>'
    content = re.sub(pattern, replacement, content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print('Done modifying detaillaporan.blade.php')

file_path = r'c:\Users\Hujan Deras\Downloads\PPKPT-master\PPKPT-master\resources\views\satgas\investigasi.blade.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace('<head>', '<head>\n    <meta name="csrf-token" content="{{ csrf_token() }}">')
content = content.replace('<body>', '<body x-data="satgasDecryptPage({{ $aduan->id }})">')

content = content.replace('<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">', modal_html + '\n                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">', 1)

for field in fields:
    pattern = r'\{\{\s*\$aduan->' + field + r'\s*\?\?\s*\'-\'\s*\}\}'
    replacement = f'<span x-show="!isDecrypted">{{{{ $aduan->{field} ?? \'-\' }}}}</span><span x-show="isDecrypted" x-text="decrypted ? decrypted.{field} : \'-\'"></span>'
    content = re.sub(pattern, replacement, content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print('Done modifying investigasi.blade.php')

