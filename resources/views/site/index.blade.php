<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wibufest — Films</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .glass {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card-hover {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-hover:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      background: rgba(255, 255, 255, 0.08);
    }

    .btn-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      transition: all 0.3s ease;
    }

    .btn-gradient:hover {
      background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .text-glow {
      text-shadow: 0 0 30px rgba(255, 255, 255, 0.5);
    }

    .logo-container {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .price-badge {
      background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }

    .floating {
      animation: float 6s ease-in-out infinite;
    }
  </style>
</head>
<body class="min-h-screen p-4 md:p-8 lg:p-12">
  <div class="max-w-7xl mx-auto">
    <!-- Header with Logo -->
    <div class="logo-container rounded-2xl p-6 mb-12 text-center floating">
      <div class="flex items-center justify-center mb-4">
        <div class="w-12 h-12 bg-gradient-to-r from-purple-400 to-pink-400 rounded-lg flex items-center justify-center mr-4">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 8v8a1 1 0 001 1h8a1 1 0 001-1v-8M7 8h10"></path>
            <circle cx="12" cy="12" r="3"></circle>
          </svg>
        </div>
        <h1 class="text-4xl md:text-6xl font-extrabold text-white text-glow">
          Wibufest Films
        </h1>
      </div>
      <p class="text-white/80 text-lg md:text-xl font-medium">
        Pilih film favorit Anda dan nikmati pengalaman menonton terbaik
      </p>
    </div>

    <!-- Films Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
      @foreach($films as $f)
      <div class="glass rounded-3xl overflow-hidden card-hover">
        <!-- Card Header with Gradient -->
        <div class="h-2 bg-gradient-to-r from-purple-400 via-pink-400 to-red-400"></div>

        <div class="p-8">
          <!-- Film Icon -->
          <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-purple-500 rounded-2xl flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
          </div>

          <!-- Film Title -->
          <h2 class="text-2xl font-bold text-white mb-4 leading-tight">
            {{ $f['title'] }}
          </h2>

          <!-- Price Badge -->
          <div class="price-badge rounded-full px-4 py-2 inline-block mb-6">
            <p class="text-white font-semibold text-lg">
              Rp {{ number_format($f['price'],0,',','.') }}
            </p>
          </div>

          <!-- Select Button -->
          <a href="{{ route('film.seats', $f['id']) }}"
             class="btn-gradient block w-full text-center px-6 py-4 text-white font-semibold rounded-xl text-lg">
            <span class="flex items-center justify-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Pilih Kursi
            </span>
          </a>
        </div>
      </div>
      @endforeach
    </div>

    <!-- Footer -->
    <footer class="mt-16 text-center">
      <div class="glass rounded-2xl p-6 inline-block">
        <p class="text-white/80 text-lg">
          Admin?
          <a href="/admin" class="text-cyan-300 hover:text-cyan-200 font-semibold ml-2 inline-flex items-center transition-colors duration-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Masuk Admin
          </a>
        </p>
      </div>
    </footer>
  </div>
</body>
</html>
