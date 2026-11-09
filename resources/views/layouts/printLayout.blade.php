<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Print')</title>

    <style>
        /* RESET EVERYTHING */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        html, body {
            width: 80mm !important;
            height: auto !important;
            min-height: 0 !important;
            margin: 0 !important;
            padding: 0 !important;

            font-family: "Courier New", monospace !important;
            font-size: 15px !important;
            line-height: 1.25 !important;
            background: white !important;
        }

        .invoice-preview {
            width: 80mm !important;
            max-width: 80mm !important;
            height: auto !important;
            min-height: 0 !important;
            margin: 0 auto !important;
            padding: 0 !important;
        }

        .invoice-preview-card {
            height: auto !important;
            min-height: 0 !important;
            max-height: none !important;
        }

        .center {
            text-align: center !important;
            width: 100%;
        }
    </style>

    @stack('styles')
</head>

<body>
    @yield('content')

    @stack('scripts')
</body>
</html>
