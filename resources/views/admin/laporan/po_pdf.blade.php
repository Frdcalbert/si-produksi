<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Purchase Order</title>
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
        .stats {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
        }
        .stats table {
            width: 100%;
            font-size: 11px;
            border-collapse: collapse;
        }
        .stats td {
            padding: 4px 8px;
            border: 1px solid #ddd;
        }
        .stats .label {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 40%;
        }
        .stats .value {
            width: 60%;
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
        .footer .signature {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
    </style>
</head>
<body>
    <div class="header">
        <h4 class="text-left"><strong>PT INDO RISAKTI</strong></h4>
        <h2>LAPORAN PURCHASE ORDER</h2>
        @if($tanggal_awal && $tanggal_akhir)
            <p>Periode: {{ date('d-m-Y', strtotime($tanggal_awal)) }} - {{ date('d-m-Y', strtotime($tanggal_akhir)) }}</p>
        @endif
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <table>
            <tr>
                <td class="label">Total PO</td>
                <td class="value">{{ $totalPO ?? 0 }}</td>
                <td class="label">PO Selesai</td>
                <td class="value">{{ $totalSelesai ?? 0 }}</td>
            </tr>
            <tr>
                <td class="label">PO Diproses</td>
                <td class="value">{{ $totalProses ?? 0 }}</td>
                <td class="label">Total Kuantitas</td>
                <td class="value">{{ number_format($totalQty ?? 0) }} Unit</td>
            </tr>
            <tr>
                <td class="label">PO Menunggu</td>
                <td class="value">{{ $totalMenunggu ?? 0 }}</td>
                <td class="label">Total Menunggu</td>
                <td class="value">{{ $totalMenunggu ?? 0 }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>NO PO</th>
                <th>TGL PO</th>
                <th>PROJECT</th>
                <th>SUPPLIER</th>
                <th>KODE</th>
                <th>NAMA PRODUK</th>
                <th>QTY</th>
                <th>DEADLINE</th>
                <th>STATUS</th>
                <th>KETERANGAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrders ?? [] as $po)
                @foreach($po->detailPo as $detail)
                    <tr>
                        <td><strong>{{ $po->no_po }}</strong></td>
                        <td>{{ date('d-m-Y', strtotime($po->tanggal_po)) }}</td>
                        <td>{{ $po->project->no_project ?? '-' }}</td>
                        <td>{{ $po->supplier->nama_supplier ?? '-' }}</td>
                        <td>{{ $detail->produk->kode_produk ?? '-' }}</td>
                        <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                        <td class="text-center">{{ $detail->qty_po }}</td>
                        <td>{{ date('d-m-Y', strtotime($po->deadline_po)) }}</td>
                        <td>{{ $po->status_po }}</td>
                        <td>{{ $po->catatan ?? '-' }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data purchase order</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>