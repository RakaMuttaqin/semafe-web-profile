<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        SEMAFE - @yield('title', 'Senat Mahasiswa Fakultas Ekonomi')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="min-h-screen bg-[#FFFDF7] font-sans antialiased">

    <main class="min-h-screen">

        @yield('content')

    </main>


    @stack('scripts')

</body>

</html>
