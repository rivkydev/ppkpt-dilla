<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"> -->
    <!-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> -->
    <script>
    const otpTargetTime = {{ session('otpTargetTime') ?? 'null' }};
    if (otpTargetTime) {
        localStorage.setItem('otpTargetTime', otpTargetTime);
    }
</script>
    
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-lg w-full bg-white shadow-lg rounded-lg p-8">
            <!-- Logo -->
            <img class="w-auto h-40 mx-auto" src="{{ asset('img/logo.png') }}" alt="Logo">

            <!-- Judul -->
            <h1 class="text-2xl font-bold mb-4 text-center text-gray-800">
                Verifikasi Email Anda
            </h1>

            <!-- Pesan -->
            <p class="mb-6 text-gray-600 text-center">
                Kami telah mengirimkan kode OTP ke email:
                <strong class="break-all">{{ $maskedEmail }}</strong>.<br>
                Silakan cek dan masukkan kode tersebut untuk verifikasi.
            </p>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="text-red-700 italic mb-2 text-center">
                    {{ $errors->first('kode') }}
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="text-green-700 italic mb-2 text-center">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Bagian <div x-data="..."> -->
            <div
    x-data="{
        timeLeft: 0,
        timer: null,

        startTimer() {
    const now = Date.now();
    const targetTime = parseInt(localStorage.getItem('otpTargetTime'), 10);
    if (!targetTime || now >= targetTime) {
        this.timeLeft = 0;
        localStorage.removeItem('otpTargetTime');
        return;
    }
    this.updateTimeLeft();
    this.timer = setInterval(() => {
        this.updateTimeLeft();
    }, 1000);
},
updateTimeLeft() {
    const now = Date.now();
    const targetTime = parseInt(localStorage.getItem('otpTargetTime'), 10);
    if (!targetTime || now >= targetTime) {
        this.timeLeft = 0;
        clearInterval(this.timer);
        localStorage.removeItem('otpTargetTime');
    } else {
        this.timeLeft = Math.floor((targetTime - now) / 1000);
    }
},

        formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        },

        init() {
            // Apakah user baru saja klik Kirim Ulang?
            if (localStorage.getItem('resendClicked') === 'true') {
                this.startTimer(120); // reset timer 2 menit
                localStorage.removeItem('resendClicked');
            } else {
                // Jika ada targetTime tersimpan, lanjutkan timer
                const targetTime = parseInt(localStorage.getItem('otpTargetTime'), 10);
                if (!isNaN(targetTime)) {
                    this.updateTimeLeft();
                    if (this.timeLeft > 0) {
                        if (this.timer) clearInterval(this.timer);
                        this.timer = setInterval(() => {
                            this.updateTimeLeft();
                        }, 1000);
                    } else {
                        localStorage.removeItem('otpTargetTime');
                    }
                }
            }
        }
    }"
    x-init="init() {
    const targetTime = parseInt(localStorage.getItem('otpTargetTime'), 10);
    const now = Date.now();

    // 1️⃣ Kalau user baru saja klik 'Kirim Ulang', mulai timer baru.
    if (localStorage.getItem('resendClicked') === 'true') {
        this.startTimer(120);
        localStorage.removeItem('resendClicked');
    } 
    // 2️⃣ Kalau targetTime masih valid, teruskan timer.
    else if (!isNaN(targetTime) && targetTime > now) {
        this.updateTimeLeft();
        this.timer = setInterval(() => {
            this.updateTimeLeft();
        }, 1000);
    } 
    // Kalau targetTime sudah lewat atau belum ada sama sekali, bersihkan.
    else {
        this.timeLeft = 0;
        localStorage.removeItem('otpTargetTime');
    }
}
"
>


                <!-- Form OTP -->
                <form action="{{ route('verify.otp') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="flex justify-center">
                        <div class="flex w-full max-w-md">
                            <input 
                                type="text" 
                                name="kode" 
                                placeholder="Kode OTP" 
                                class="flex-1 rounded-l-xl bg-white px-5 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#F08619]"
                                required
                            >
                            <button 
                                type="submit" 
                                class="bg-[#F08619] hover:bg-[#3B6BA2] text-white font-semibold px-6 py-2 rounded-r-xl border border-l-0 border-gray-300">
                                Verifikasi
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Timer & Resend OTP -->
                <div class="mt-4">
                    <div class="flex items-center justify-between">
                        <span x-text="formatTime(timeLeft)" class="text-gray-600"></span>
                        <form 
                            method="POST" 
                            action="{{ route('verify.resend') }}" 
                            x-ref="resendForm"
                        >
                            @csrf
                            <p class="text-sm italic text-gray-600">
                                Belum menerima kode OTP?
                                <button 
                                    type="button"
                                    class="ml-2 italic text-[#008CFF] text-sm hover:text-[#0056B3] hover:underline disabled:opacity-50"
                                    :disabled="timeLeft > 0"
                                    @click.prevent="
                                        // Set localStorage flag agar timer bisa diinit di halaman reload
                                        localStorage.setItem('resendClicked', 'true');
                                        // Submit form secara manual
                                        $refs.resendForm.submit();
                                    "
                                >
                                    Kirim Ulang
                                </button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
