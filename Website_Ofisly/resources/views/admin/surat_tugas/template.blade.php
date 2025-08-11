<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .content { margin: 20px 50px; }
        .footer { margin-top: 50px; text-align: right; }
        .title { font-weight: bold; font-size: 16pt; }
        .no-surat { font-weight: bold; margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SURAT TUGAS</div>
        <div class="no-surat">Nomor: {{ $surat->no_surat }}</div>
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini, memberikan tugas kepada:</p>

        <table border="0" cellpadding="5">
            <tr>
                <td width="150">Nama</td>
                <td width="20">:</td>
                <td>{{ $surat->nama_kandidat }}</td>
            </tr>
            <tr>
                <td>Tanggal Penugasan</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($surat->tgl_penugasan)->format('d F Y') }}</td>
            </tr>
        </table>

        <p style="margin-top: 30px;">Demikian surat tugas ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="footer">
        <p>Surabaya, {{ \Carbon\Carbon::parse($surat->tgl_surat_pembuatan)->format('d F Y') }}</p>
        <br><br><br>
        <p>(_______________________)</p>
    </div>
</body>
</html>
