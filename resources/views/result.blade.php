<!DOCTYPE html>
<html>
<head>
    <title>Hasil Grey AHP</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .info { background:#f3f4f6; padding:10px; border-radius:6px; margin-bottom:15px; }
        .warning { background:#fff3cd; color:#856404; padding:10px; border-radius:6px; margin-bottom:15px; }
        .success { background:#e7f7ee; color:#065f46; padding:10px; border-radius:6px; margin-bottom:15px; }
    </style>
</head>
<body>

<h3>Hasil Perhitungan Grey AHP</h3>


<h4>Pakar 1</h4>
@if(isset($CR))
    @if($CR < 0.1)
        <div class="success">
            <strong>Uji Konsistensi:</strong> 
            <br> CR = {{ number_format($CR,4) }} (Konsisten)
            <br> CI = {{ number_format($CI,4) }}
            <br> Lambda Max = {{ number_format($lambda_max,4) }}
        </div>
    @else
        <div class="warning">
            <strong>Uji Konsistensi:</strong>
            <br> CR = {{ number_format($CR,4) }} (Tidak konsisten)
            <br> CI = {{ number_format($CI,4) }}
            <br> Lambda Max = {{ number_format($lambda_max,4) }}
        </div>
    @endif
@endif

<h4>Pakar 2</h4>
@if(isset($CR2))
    @if($CR < 0.1)
        <div class="success">
            <strong>Uji Konsistensi:</strong> 
            <br> CR = {{ number_format($CR2,4) }} (Konsisten)
            <br> CI = {{ number_format($CI2,4) }}
            <br> Lambda Max = {{ number_format($lambda_max,4) }}
        </div>
    @else
        <div class="warning">
            <strong>Uji Konsistensi:</strong>
            <br> CR = {{ number_format($CR,4) }} (Tidak konsisten)
            <br> CI = {{ number_format($CI,4) }}
            <br> Lambda Max = {{ number_format($lambda_max,4) }}
        </div>
    @endif
@endif

<h4>Bobot Kriteria</h4>
<ul>
@foreach($weights ?? [] as $k => $v)
    <li>{{ $k }} : {{ number_format($v,4) }}</li>
@endforeach
</ul>

</body>
</html>
