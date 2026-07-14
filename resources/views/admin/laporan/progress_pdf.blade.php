<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Progress Produksi</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px;
            margin: 20px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 15px; 
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
            color: #666;
        }
        .header h3 {
            margin-top: 10px;
            font-size: 14px;
        }
        .info {
            margin: 10px 0;
            padding: 8px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .info table {
            width: 100%;
            font-size: 11px;
        }
        .info td {
            padding: 3px 5px;
        }
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            font-size: 10px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 4px; 
            text-align: left;
        }
        .table th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .badge-success { color: #28a745; font-weight: bold; }
        .badge-warning { color: #ffc107; font-weight: bold; }
        .badge-info { color: #17a2b8; font-weight: bold; }
        .footer {
            margin-top: 15px;
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h4 class="text-left"><strong>PT INDO RISAKTI</strong></h4>
        <h2>LAPORAN PROGRESS PRODUKSI</h2>
        @if($filter && $filter != 'Semua')
            <p>Status: {{ $filter }}</p>
        @endif
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="15%"><strong>Total Record : </strong></td>
                <td>{{ $totalRecords ?? 0 }} Laporan</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>NO PO</th>
                <th>KODE PRODUK</th>
                <th>NAMA PRODUK</th>
                <th>TANGGAL PO</th>
                <th>DEADLINE</th>
                <th>PROJECT</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrders ?? [] as $po)
                @foreach($po->detailPo as $detail)
                    <tr>
                        <td><strong>{{ $po->no_po }}</strong></td>
                        <td>{{ $detail->produk->kode_produk ?? '-' }}</td>
                        <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                        <td>{{ date('d-m-Y', strtotime($po->tanggal_po)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($po->deadline_po)) }}</td>
                        <td>{{ $po->project->no_project ?? '-' }}</td>
                        <td>{{ $po->status_po }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data progress</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 10px; font-size: 10px; color: #666;">
        Menampilkan {{ $purchaseOrders->sum(function($po) { return $po->detailPo->count(); }) ?? 0 }} Progress Produksi dari {{ $totalRecords ?? 0 }} Ref PO
    </div>
</body>
</html>