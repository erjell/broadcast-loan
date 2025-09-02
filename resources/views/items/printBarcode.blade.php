<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            margin: 0;
            padding: 16px;
        }

        .wrap {
            display: flex;
            min-height: 100vh;
        }

        .card {
            /* border: 1px solid #e5e7eb; */
            border-radius: 8px;
            padding: 16px 20px;
        }

        .title {
            font-size: 14px;
            margin-bottom: 8px;
            color: #374151;
            text-align: center;
        }

        .meta {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        .controls {
            margin-top: 10px;
            text-align: center;
        }

        .btn {
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: white;
            cursor: pointer;
        }

        .btn:hover {
            background: #f9fafb;
        }

        @media print {
            .controls {
                display: none;
            }

            body {
                padding: 0;
            }

            .card {
                border: none;
            }
        }

        .barcode-wrap {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .barcode-wrap svg {
            width: 100%;
            height: auto;
            max-height: 70mm;
        }
    </style>
    <script>
        // window.addEventListener('load', function(){
        //     // Auto open print dialog on load for convenience
        //     window.print();
        // });
    </script>
    @php
    use Milon\Barcode\DNS1D;
    $dns = new DNS1D();
    $dns->setStorPath(storage_path('framework/cache'));
    @endphp
</head>

<body>

    @php
    $label = $type === 'serial' ? ($item->serial_number ?: '-') : $item->code;
    $caption = $type === 'serial' ? 'Serial' : 'Kode';
    @endphp
    <div class="wrap">
        <div class="card">
            <div class="title">{{ $item->name }}</div>
            <div class="barcode-wrap">
                @if($type === 'serial')
                @if(!empty($item->serial_number))
                {!! $dns->getBarcodeSVG($item->serial_number, 'C128', 2, 60, 'black', true) !!}
                @else
                <div class="meta">Serial number tidak tersedia</div>
                @endif
                @else
                {!! $dns->getBarcodeSVG($item->code, 'C128', 2, 60, 'black', true) !!}
                @endif
            </div>
            <div class="controls">
                <button class="btn" onclick="window.print()">Cetak</button>
            </div>
        </div>
    </div>
</body>

</html>
