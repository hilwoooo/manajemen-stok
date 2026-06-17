<?php
require_once  '../vendor/autoload.php';
require_once '../Config/koneksi.php';
require_once 'dashboard_data.php';
require_once 'tampil_produk.php';

$tampil = getPenjualan();

use Dompdf\Dompdf;
use Dompdf\Options;



$tampil_total_penjualan = $total_penjualan;
$tampil_total_pendapatan = number_format($total_pendapatan_penjualan, 0, ',', '.');
$tampil_jumlah_stok = $total_barang;

$html = '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            border: 1px solid #000;
            padding: 8px;

            text-align: center;
        }

        td {
            border: 1px solid #000;
            padding: 8px;
        }

        .deskp{
            margin: 5px 7px;
        }

        #sl{
            font-size: 14px;
            font-weight: 600;
        }

        th{
            background-color: rgb(58, 96, 173);
            color: #ffff
        }

        .container{
            background-color: rgb(195, 198, 199);
            padding: 9px;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <h2>Laporan Penjualan</h2>
    <div class="container">
    <div class="deskp">
        <p id="sl">Total Penjualan : ' . $tampil_total_penjualan . '</p>
        <p id="sl">Total Pendapatan Penjualan : ' . $tampil_total_pendapatan . '</p>
        <p id="sl">Jumlah Stok : ' . $tampil_jumlah_stok . '</p>
    </div>

    <table>
            <thead>
                <tr class="bg-primary text-white">
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Pembeli</th>
                    <th class="text-center">No. HP</th>
                    <th class="text-center">Sparepart Keluar</th>
                    <th class="text-center">Kategori</th>
                    <th class="text-center">Harga Jual Transaksi</th>
                    <th class="text-center">Tanggal Transaksi</th>
            </thead>
        </tr>

        <tbody>
';


$i = 1;

while ($data = mysqli_fetch_array($tampil)) {
    $html .= '
            <tr>
                    <td>' . $i++ . '</td>
                    <td><strong> ' . $data['nama_pembeli'] . '</strong></td>
                    <td>' . $data['no_hp'] . '</td>
                    <td>' . $data['merek'] . ' - ' . $data['nama_produk'] . '</td>
                    <td>' . $data['nama_kategori'] . '</td>
                    <td><strong> Rp' . number_format($data['total_bayar'], 0, ',', '.') . '</strong></td>
                    <td>' . date('d-m-Y H:i', strtotime($data['tanggal_keluar'])) . 'WIB</td>
                </tr>
            
            ';
}


$html .= '
    </tbody>
    </table>
</div>
</body>

</html>
    ';

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream(
    'laporan_penjualan.pdf',
    ['Attachment' => false]
);
