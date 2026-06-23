<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Prioritas Aduan - MARCOS</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        h2 {
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .tinggi {
            background-color: #f8d7da;
        }
        .menengah {
            background-color: #fff3cd;
        }
        .rendah {
            background-color: #d1e7dd;
        }
    </style>
</head>
<body>

<h2>Hasil Penentuan Prioritas Laporan Menggunakan Metode MARCOS</h2>

<table>
    <thead>
        <tr>
            <th>Peringkat</th>
            <th>Kode Aduan</th>
            <th>Nilai f(Ci)</th>
            <th>Kategori Prioritas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($hasil as $row)
            <tr class="
                @if($row['kategori'] == 'Tinggi') tinggi
                @elseif($row['kategori'] == 'Menengah') menengah
                @else rendah
                @endif
            ">
                <td>{{ $row['peringkat'] }}</td>
                <td>Aduan {{ $row['aduan_index'] + 1 }}</td>
                <td>{{ $row['nilai'] }}</td>
                <td>{{ $row['kategori'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<p style="margin-top:20px;">
    Keterangan:
    <br>• <strong>Tinggi</strong> : Laporan dengan prioritas penanganan paling mendesak  
    <br>• <strong>Menengah</strong> : Laporan dengan prioritas penanganan sedang  
    <br>• <strong>Rendah</strong> : Laporan dengan prioritas penanganan rendah  
</p>

</body>
</html>
