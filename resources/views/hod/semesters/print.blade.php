<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $department->abbreviation }}-{{ $semesterNo }}-semester</title>
    @vite('resources/css/app.css')

</head>

<body class="px-15 py-12">
    <style>
        @media print {
            @page {
                margin: 0.35in;
            }

            body {
                margin: 2px;
                padding: 30px;
            }
        }
    </style>
    @include('partials.semester_preview')
</body>
<script>
    window.onload = function() {
        window.print();
    };

    window.onafterprint = function() {
        window.close();
    };
</script>

</html>
