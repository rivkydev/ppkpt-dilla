<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>PPKPT ITH</title>
 @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- <link
          rel="stylesheet"  
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    </head>
    <body class="bg-login min-h-screen flex items-center justify-center">
    <div class="bg-[rgba(217,217,217,0.1)] rounded-2xl shadow-lg lg:px-23 px-18 py-12 w-full max-w-lg">
        <!-- Logo -->
        <div class="flex justify-center">
        <img class="h-50 w-auto" src="img/logo.png" alt="Logo">
        </div>

        <!-- Form Login -->
        <form class="space-y-4" action="/login" method="POST">
        @csrf
        <!-- Username -->
        <div class="form-login">
            <i class="fa-regular fa-user absolute py-3 px-4"></i>
            <input type="text" name="username" id="username" placeholder="Username" required
            class="block w-full rounded-full bg-white pl-10 py-2 pr-5 text-base text-gray-900 border border-gray-300 placeholder:text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#F08619] sm:text-sm">
        </div>
        <!-- Password -->
        <div class="form-login">
            <i class="fa-solid fa-lock absolute py-3 px-4"></i>
            <input type="password" name="password" id="password" placeholder="Password" autocomplete="current-password" required
            class="block w-full rounded-full bg-white pl-10 py-2 pr-5 text-base text-gray-900 border border-gray-300 placeholder:text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#F08619] sm:text-sm">
        </div>

        <!-- Error Message -->
        @if(session('error'))
            <div class="text-red-500 text-center">
                <p class="text-md">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Tombol -->
        <div class="space-y-3 mt-10">
            <button type="submit"
            class="cursor-pointer w-full justify-center rounded-full bg-[#F08619] px-5 py-3 text-sm font-semibold text-gray-50 hover:bg-[#3B6BA2] tracking-wider text-lg">Masuk</button>
            <a href="/"
            class="block text-center w-full rounded-full bg-gray-50 px-5 py-3 text-sm font-normal text-gray-900 hover:bg-gray-200 tracking-wider text-lg">Masuk Sebagai Tamu</a>
        </div>
        </form>
    </div>
    <x-faq></x-faq>
    </body>

</html>