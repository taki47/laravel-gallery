<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('gallery::gallery.admin.title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f7f7f7;
            color: #222;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.08);
        }

        h1 {
            margin-top: 0;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
        }

        .btn {
            display: inline-block;
            padding: 10px 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            text-decoration: none;
            color: #222;
            background: #f3f3f3;
            cursor: pointer;
        }

        .btn:hover {
            background: #e9e9e9;
        }

        .btn-primary {
            background: #222;
            color: #fff;
            border-color: #222;
        }

        .btn-primary:hover {
            background: #111;
        }

        .btn-danger {
            background: #b42318;
            color: #fff;
            border-color: #b42318;
        }

        .btn-danger:hover {
            background: #911d14;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #e5e5e5;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #fafafa;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .form-control,
        textarea,
        input[type="text"],
        input[type="date"] {
            width: 100%;
            max-width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #ecfdf3;
            color: #067647;
            border: 1px solid #abefc6;
        }

        .alert-danger {
            background: #fef3f2;
            color: #b42318;
            border: 1px solid #fecdca;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .inline-form {
            display: inline;
        }

        .muted {
            color: #666;
            font-size: 14px;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .errors-list {
            margin: 0;
            padding-left: 18px;
        }

        .horizontal-line {
            border-bottom: 1px solid #000;
            height:1px;
            margin: 10px 0px;
        }
    </style>
</head>
<body>
<div class="container">
    @yield('content')
</div>
</body>
</html>