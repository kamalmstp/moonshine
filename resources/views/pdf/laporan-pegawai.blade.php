<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pegawai</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Laporan Pegawai</h2>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Golongan</th>
                <th>Tanggal Masuk</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawais as $pegawai)
            <tr>
                <td>{{ $pegawai->nama_pegawai }}</td>
                <td>{{ $pegawai->nip }}</td>
                <td>{{ $pegawai->jabatan }}</td>
                <td>{{ $pegawai->golongan }}</td>
                <td>{{ $pegawai->tanggal_masuk }}</td>
                <td>{{ $pegawai->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>