<html>
    <head>
        <title>Cetak PDF</title>
</head>
<body>
    <style type="text/css">
        .table1 {
            font-family: sans-serif;
            color: #232323;
            border-collapse: collapse;
        }

        .table1, th, td {
            border: 1px solid #999;
            padding: 8px 20px;
        }
    </style>
    <h4 align="center">Laporan Data Produk</h4>
        <table class="table table-bordered table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width:5%">No.</th>
                    <th style="width:7%">Kode Produk</th>
                    <th style="width:12%">Nama Produk</th>
                    <th style="width:12%">Kategori</th>
                    <th style="width:12%">Harga</th>
                    <th style="width:8%">Stok</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataProduk as $data)
                <tr>
                <td> {{ $loop->iteration }}</td>
                <td> {{ $data->id }}</td>
                <td> {{ $data->nama_produk }}</td>
                <td> {{ $data->kategori->nama_kategori }}</td>
                <td> {{ number_format($data->harga, 0, ',' , '.') }}</td>
                <td> {{ $data->stock }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <script type="text/javacsript">
            window.print();
        </script>
    </body>
</html>