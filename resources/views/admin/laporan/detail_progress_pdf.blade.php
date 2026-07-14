<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Detail Progress Produksi</title>
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
            font-size: 9px;
        }
        .table th, .table td { 
            border: 1px solid #ddd; 
            padding: 3px; 
            text-align: center;
        }
        .table th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .footer {
            margin-top: 15px;
            border-top: 2px solid #333;
            padding-top: 10px;
        }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h4 class="text-left"><strong>PT INDO RISAKTI</strong></h4>
        <h2>LAPORAN DETAIL PROGRESS PRODUKSI</h2>
        <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    </div>
    {{-- Info PO dan Produk --}}
    <div class="info">
        <table>
            <tr>
                <td width="15%"><strong>NO PO</strong></td>
                <td width="35%">{{ $detailPo->purchaseOrder->no_po }}</td>
                <td width="15%"><strong>DEADLINE</strong></td>
                <td width="35%">{{ date('d-m-Y', strtotime($detailPo->purchaseOrder->deadline_po)) }}</td>
            </tr>
            <tr>
                <td><strong>PROJECT</strong></td>
                <td>{{ $detailPo->purchaseOrder->project->no_project ?? '-' }}</td>
                <td><strong>SUPPLIER</strong></td>
                <td>{{ $detailPo->purchaseOrder->supplier->nama_supplier ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>KODE PRODUK</strong></td>
                <td>{{ $detailPo->produk->kode_produk ?? '-' }}</td>
                <td><strong>STATUS PO</strong></td>
                <td>{{ $detailPo->purchaseOrder->status_po }}</td>
            </tr>
            <tr>
                <td><strong>NAMA PRODUK</strong></td>
                <td>{{ $detailPo->produk->nama_produk ?? '-' }}</td>
                <td><strong>QTY</strong></td>
                <td>{{ $detailPo->qty_selesai ?? 0 }} / {{ $detailPo->qty_po ?? 0 }} pcs</td>
            </tr>
        </table>
    </div>

    @if(count($progressList) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="12%">TANGGAL</th>
                    <th width="9%">TAHAP 1</th>
                    <th width="9%">TAHAP 2</th>
                    <th width="9%">TAHAP 3</th>
                    <th width="9%">TAHAP 4</th>
                    <th width="9%">TAHAP 5</th>
                    <th width="9%">TAHAP 6</th>
                    <th width="10%">FINISHING</th>
                    <th width="8%">QC</th>
                    <th width="10%">GUDANG</th>
                    <th width="19%">CATATAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($progressList as $key => $progress)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ date('d-m-Y', strtotime($progress['tanggal'])) }}</td>
                        <td>{{ $progress['tahap_1'] }}</td>
                        <td>{{ $progress['tahap_2'] }}</td>
                        <td>{{ $progress['tahap_3'] }}</td>
                        <td>{{ $progress['tahap_4'] }}</td>
                        <td>{{ $progress['tahap_5'] }}</td>
                        <td>{{ $progress['tahap_6'] }}</td>
                        <td>{{ $progress['finishing'] }}</td>
                        <td>{{ $progress['qc'] }}</td>
                        <td>{{ $progress['gudang'] }}</td>
                        <td class="text-left">{{ $progress['catatan'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #999; font-size: 11px; margin-top: 20px;">
            Belum ada progress untuk produk ini
        </p>
    @endif
</body>
</html>