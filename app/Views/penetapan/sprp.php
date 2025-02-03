<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan Rencana Penempatan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1;
            margin: 20px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin: 20px 0;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .info-table {
            margin-bottom: 20px;
            width: 100%;
        }
        .info-table th, .info-table td {
            text-align: left;
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="header" style="margin-top: -60px;">
        <table class="info-table" style="text-align: center; margin-bottom: 0px;">
            <tr>
                <!-- <td><img src="../../public/assets/images/logo_kemenag_2.png"></td> -->
                <!-- <td><img src="<?= base_url();?>assets/images/logo_kemenag_2.png"></td> -->
                <td><img src="data:image/png;base64,<?= base64_encode(file_get_contents('assets/images/logo_kemenag_22.png')); ?>" alt="Logo"></td>
                <td style="text-align: center;">
                    <p style="margin-bottom: 0px; font-size: 21px;"><strong>KEMENTERIAN AGAMA REPUBLIK INDONESIA</strong></p>
                    <br><p style="margin-top: -10px; margin-bottom: 0px; font-size: 18px;"><strong>SEKRETARIAT JENDERAL</strong></p>
                    <br><p style="margin-top: -10px; margin-bottom: 0px; font-size: 14px;">Jalan Lapangan Banteng Barat No. 3 - 4 Jakarta</p>
                    <br><p style="margin-top: -10px; margin-bottom: 0px; font-size: 14px;">Telpon: 3811244 - 3811642 - 3811654 - 3811679 - 3811779 - 3812216</p>
                    <br><p style="margin-top: -10px; margin-bottom: 0px; font-size: 14px;">(Hunting) 34833004 - 3483005</p>
                </td>
            </tr>
        </table>
        <div style="flex: 0; height: 2px; background-color: black;"></div>
        <h3 style="text-decoration: underline;">SURAT PERNYATAAN RENCANA PENEMPATAN</h3>
        <p style="margin-top: -10px;"><strong>NOMOR: <?php echo $peserta->no_sprp; ?></strong></p>
    </div>

    <div class="content">
        <br>
        <p>Saya yang bertanda tangan di bawah ini:</p>
        <table class="info-table">
            <tr>
                <td style="width: 170px;">Nama</td>
                <td>: Dr. H. Wawan Djunaedi, M.A</td>
            </tr>
            <tr>
                <td style="width: 170px;">Jabatan</td>
                <td>: Kepala Biro Kepegawaian</td>
            </tr>
            <tr>
                <td style="width: 170px;">Unit Kerja</td>
                <td>: Biro Kepegawaian Sekretariat Jenderal</td>
            </tr>
            <tr>
                <td style="width: 170px;">Instansi</td>
                <td>: Kementerian Agama Republik Indonesia</td>
            </tr>
        </table>

        <p>Dengan ini menyatakan bahwa Saudara/i:</p>
        <table class="info-table">
            <tr>
                <td style="width: 170px;">Nama</td>
                <td>: <?php echo $peserta->nama; ?></td>
            </tr>
            <tr>
                <td style="width: 170px;">Tempat/Tanggal Lahir</td>
                <td>: <?php echo $peserta->tempat_lahir; ?>/<?php echo $peserta->tanggal_lahir; ?></td>
            </tr>
            <tr>
                <td style="width: 170px;">Pendidikan/Jurusan</td>
                <td>: <?php echo $peserta->pendidikan; ?></td>
            </tr>
            <tr>
                <td style="width: 170px;">Kebutuhan Jabatan</td>
                <td>: <?php echo $peserta->formasi; ?></td>
            </tr>
        </table>

        <p>Akan kami tempatkan pada unit kerja <?php echo $penempatan; ?> sebagai <?php echo $peserta->formasi; ?> di lingkungan <?php echo $nama_satker; ?>.</p>

        <p>Demikian surat pernyataan ini dibuat dengan sesungguhnya dan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature">
        <p>Jakarta, <?php echo $sysdate; ?></p>
        <p style="margin-top: -10px;">Kepala Biro Kepegawaian,</p>
        <br>
        <p style="margin-right: 100px;">^</p>
        <br>
        <p style="text-decoration: underline;">Dr. H. Wawan Djunaedi, M.A</p>
        <p style="margin-top: -10px;">NIP. 197706022005011005</p>
    </div>
</body>
</html>
