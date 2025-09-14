<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('judul') - Tikecs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex">
    @include('layouts.sidebar')

    <div class="flex-1 flex flex-col">
        @include('layouts.navbar')

        <main id="content" class="flex-1 p-6 lg:p-8 lg:pt-1 overflow-y-auto">
            @yield('konten')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userMenuButton = document.getElementById('userMenuButton');
            const userDropdown = document.getElementById('userDropdown');
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.getElementById('sidebar');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', () => {
                    userDropdown.classList.toggle('hidden');
                });
                document.addEventListener('click', (e) => {
                    if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }

            if (mobileMenuButton && sidebar) {
                mobileMenuButton.addEventListener('click', () => {
                    sidebar.classList.toggle('hidden');
                });
                document.addEventListener('click', (e) => {
                    if (!sidebar.contains(e.target) && !mobileMenuButton.contains(e.target) && !sidebar.classList.contains('hidden')) {
                        sidebar.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
