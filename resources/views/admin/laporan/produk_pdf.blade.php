<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Produk</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            margin: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #666;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left;
        }
        .table th { 
            background-color: #f2f2f2; 
            font-weight: bold;
            font-size: 11px;
        }
        .table td {
            font-size: 11px;
        }
        .footer {
            margin-top: 20px;
            border-top: 2px solid #333;
            padding-top: 10px;
            text-align: right;
        }
        .footer .signature {
            margin-top: 30px;
        }
        .total-info {
            margin: 10px 0;
            font-size: 12px;
            color: #555;
        }
        .text-center {
            text-align: center;
        }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h4 class="text-left"><strong>PT INDO RISAKTI</strong></h4>
        <h2>LAPORAN DATA PRODUK</h2>
        <p>Total Produk: {{ $totalProduk ?? 0 }}</p>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th width="12%">KODE</th>
                <th width="25%">NAMA PRODUK</th>
                <th width="13%">UKURAN</th>
                <th width="15%">BAHAN</th>
                <th width="10%">SATUAN</th>
                <th width="25%">KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produk ?? [] as $p)
                <tr>
                    <td><strong>{{ $p->kode_produk }}</strong></td>
                    <td>{{ $p->nama_produk }}</td>
                    <td>{{ $p->ukuran }}</td>
                    <td>{{ $p->bahan }}</td>
                    <td class="text-center">{{ $p->satuan }}</td>
                    <td>{{ $p->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-info">
        Menampilkan {{ $produk->count() ?? 0 }} dari {{ $totalProduk ?? 0 }} produk
    </div>
</body>
</html>