@php
$office = config('app.name', '');
$city = config('app.office_city', 'Jakarta');
try {
$dateObj = $loan->loan_date instanceof \Carbon\Carbon
? $loan->loan_date
: (\Carbon\Carbon::parse((string) $loan->loan_date));
$date = $dateObj->translatedFormat('d F Y');
} catch (\Throwable $e) {
$date = now()->format('d F Y');
}
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serah Terima Barang - {{ $loan->code }}</title>
    <style>
        @page {
            margin: 40px 48px;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin: 0 0 18px;
            text-decoration: underline;
        }

        .muted {
            color: #333;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mb-3 {
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #555;
            padding: 6px 8px;
            vertical-align: top;
        }

        th {
            background: #efefef;
            text-align: center;
        }

        .no-border {
            border: 0 !important;
        }

        .signature-block {
            margin-top: 48px;
            width: 100%;
        }

        .sig-col {
            width: 50%;
        }

        .sig-title {
            margin-bottom: 56px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <h1>SERAH TERIMA BARANG</h1>
    <p class="mb-2">Diserahkan peralatan berupa:</p>

    <table class="mb-3">
        <thead>
            <tr>
                <th style="width:20px">No</th>
                <th>Nama Barang</th>
                <th style="width:45px">Kode</th>
                <th style="width:220px">Serial Number</th>
                <th style="width:120px">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loan->items as $i => $li)
            @php
            $serial = trim((string) $li->item->serial_number);
            $keterangan = $li->item->condition ? ucfirst(str_replace('_',' ', $li->item->condition)) : 'Baik';
            @endphp
            <tr>
                <td class="center">{{ $i+1 }}</td>
                <td>{{ $li->item->name }}</td>
                <td class="center">{{ $li->item->code }}</td>
                <td>{!! nl2br(e($serial)) !!}</td>
                <td class="center">{{ $keterangan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="mb-2">
        Barang tersebut di atas sudah dilakukan perbaikan dan sudah dilakukan uji coba dalam keadaan baik. Selanjutnya akan diserahkan kembali ke {{ $loan->partner->name }}.
    </p>
    <p class="mb-3">
        Demikian surat tanda terima barang tersebut, untuk dapat dipergunakan dengan sebagaimana mestinya.
    </p>

    <table class="no-border signature-block">
        <tr class="no-border">
            <td class="no-border sig-col"></td>
            <td class="no-border sig-col center">{{ $city }}, {{ $date }}</td>
        </tr>
        <tr class="no-border">
            <td class="no-border sig-col center">Yang menerima,<br>{{ $loan->partner->name }}</td>
            <td class="no-border sig-col center ">Yang menyerahkan,<br>{{ $loan->user->name }}</td>
        </tr>
        <tr class="no-border">
            <td class="no-border sig-col sig-title">&nbsp;</td>
            <td class="no-border sig-col sig-title">&nbsp;</td>
        </tr>
        <tr class="no-border">
            <td class="no-border sig-col sig-title">&nbsp;</td>
            <td class="no-border sig-col sig-title">&nbsp;</td>
        </tr>
        <tr class="no-border">
            <td class="no-border sig-col sig-title">&nbsp;</td>
            <td class="no-border sig-col sig-title">&nbsp;</td>
        </tr>
        <tr class="no-border">
            <td class="no-border sig-col center">(__________________)</td>
            <td class="no-border sig-col center">(__________________)</td>
        </tr>
    </table>
</body>

</html>