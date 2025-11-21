<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Film — Admin Wibufest</title>
<link rel="icon" href="{{ asset('favicon.ico') }}">
<script>
  // Force dark mode permanently
  document.documentElement.classList.add('dark');
</script>
<script src="https://cdn.tailwindcss.com" defer></script>
<script>tailwind.config = { darkMode: 'class' }</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom-inputs.css') }}">
<style>
  body { font-family: 'Inter', sans-serif; }
  ::selection { background: #ef4444; color: white; }
  .dark ::selection { background: #f87171; color: #111827; }
</style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-950 dark:to-gray-900 min-h-screen text-gray-800 dark:text-gray-100 transition-colors">
  @include('partials.navbar')

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 mb-4 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Dashboard
          </a>
          <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-red-600 to-pink-600 dark:from-red-400 dark:to-pink-400 bg-clip-text text-transparent">
            Kelola Film
          </h1>
          <p class="text-gray-600 dark:text-gray-400 mt-2">Tambah dan edit film festival</p>
        </div>
      </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
      <div class="glass-card bg-green-50/50 dark:bg-green-900/10 border-green-200 dark:border-green-800 mb-6 animate-slide-up">
        <div class="flex items-center gap-3">
          <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <p class="text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
      </div>
    @endif
    @if(session('error'))
      <div class="glass-card bg-red-50/50 dark:bg-red-900/10 border-red-200 dark:border-red-800 mb-6 animate-slide-up">
        <div class="flex items-center gap-3">
          <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-red-800 dark:text-red-300">{{ session('error') }}</p>
        </div>
      </div>
    @endif

    <!-- Form tambah film -->
    <div class="glass-card mb-8">
      <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200/50 dark:border-gray-700/50">
        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
        </div>
        <div>
          <h2 class="text-xl font-semibold">Tambah Film Baru</h2>
          <p class="text-sm text-gray-600 dark:text-gray-400">Upload poster dan buat daftar kursi</p>
        </div>
      </div>

      <form method="POST" action="{{ route('admin.films.store') }}" enctype="multipart/form-data" class="space-y-6" id="filmForm">
        @csrf

        <!-- Title & Price -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="block mb-2 text-sm font-medium flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
              </svg>
              Judul Film
              <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              name="title"
              value="{{ old('title') }}"
              placeholder="Contoh: Pengabdi Setan 2 Communion"
              style="width: 100%; padding: 0.875rem 1.125rem; border: 2px solid #4b5563; border-radius: 0.875rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.9375rem; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.4); transition: all 0.2s;"
              class="@error('title') border-red-500 @enderror"
              required
              maxlength="255">
            @error('title')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ $message }}
              </p>
            @enderror
            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Maksimal 255 karakter</p>
          </div>

          <div>
            <label class="block mb-2 text-sm font-medium flex items-center gap-2">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Harga Tiket
              <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium z-10 pointer-events-none"></span>
              <input
                type="number"
                name="price"
                value="{{ old('price') }}"
                placeholder=""
                style="width: 100%; padding: 0.875rem 1.125rem 0.875rem 3.25rem; border: 2px solid #4b5563; border-radius: 0.875rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.9375rem; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.4); transition: all 0.2s;"
                class="@error('price') border-red-500 @enderror"
                step="1000"
                min="0"
                required
                id="priceInput">
            </div>
            @error('price')
              <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                {{ $message }}
              </p>
            @enderror
            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400" id="priceFormatted">Kelipatan Rp 1.000</p>
          </div>
        </div>

        <!-- Poster Upload with Preview -->
        <div>
          <label class="block mb-2 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Poster Film
            <span class="text-gray-500 text-xs font-normal">(opsional)</span>
          </label>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Upload Input -->
            <div>
              <input
                type="file"
                name="cover"
                id="coverInput"
                accept="image/jpeg,image/png,image/jpg"
                style="width: 100%; padding: 0.625rem 0.875rem; border: 2px solid #4b5563; border-radius: 0.875rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.9375rem; box-shadow: 0 2px 4px rgba(0,0,0,0.4); cursor: pointer;"
                class="@error('cover') border-red-500 @enderror">
              @error('cover')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                  {{ $message }}
                </p>
              @enderror
              <div class="mt-2 space-y-1">
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                  </svg>
                  Format: JPG, PNG, JPEG
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                  </svg>
                  Ukuran maksimal: 5 MB
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                  <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                  </svg>
                  Rekomendasi: 600x900px (2:3)
                </p>
              </div>
            </div>

            <!-- Preview -->
            <div>
              <div id="posterPreview" class="hidden">
                <p class="text-xs text-gray-600 dark:text-gray-400 mb-2 font-medium">Preview:</p>
                <div class="relative w-48 h-72 mx-auto lg:mx-0 rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-700 shadow-lg">
                  <img id="previewImage" src="" alt="Preview" class="w-full h-full object-cover">
                  <button type="button" onclick="clearPoster()" class="absolute top-2 right-2 w-8 h-8 bg-red-600 hover:bg-red-700 text-white rounded-full flex items-center justify-center transition-colors shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </button>
                </div>
              </div>
              <div id="posterPlaceholder" class="w-48 h-72 mx-auto lg:mx-0 rounded-lg bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 flex flex-col items-center justify-center text-gray-400">
                <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-sm">Poster Preview</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Seats List -->
        <div>
          <label class="block mb-2 text-sm font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            Daftar Kursi
            <span class="text-gray-500 text-xs font-normal">(opsional)</span>
          </label>
          <textarea
            name="seats"
            rows="12"
            style="width: 100%; padding: 0.875rem 1.125rem; border: 2px solid #4b5563; border-radius: 0.875rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.875rem; font-weight: 500; font-family: monospace; box-shadow: 0 2px 4px rgba(0,0,0,0.4); resize: vertical; min-height: 280px;"
            class="@error('seats') border-red-500 @enderror"
            placeholder="Format sesuai denah bioskop (10 kolom x 10 baris):&#10;&#10;A1, A2, A3, A4, A5, A6, A7, A8, A9, A10&#10;B1, B2, B3, B4, B5, B6, B7, B8, B9, B10&#10;C1, C2, C3, C4, C5, C6, C7, C8, C9, C10&#10;D1, D2, D3, D4, D5, D6, D7, D8, D9, D10&#10;E1, E2, E3, E4, E5, E6, E7, E8, E9, E10&#10;F1, F2, F3, F4, F5, F6, F7, F8, F9, F10&#10;G1, G2, G3, G4, G5, G6, G7, G8, G9, G10&#10;H1, H2, H3, H4, H5, H6, H7, H8, H9, H10&#10;I1, I2, I3, I4, I5, I6, I7, I8, I9, I10&#10;J1, J2, J3, J4, J5, J6, J7, J8, J9, J10"
            id="seatsInput">{{ old('seats') }}</textarea>
          @error('seats')
            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
              <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
              {{ $message }}
            </p>
          @enderror
          <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-xs text-blue-800 dark:text-blue-300 flex items-start gap-2">
              <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
              <span>
                <strong>Tips:</strong> Pisahkan setiap kursi dengan <strong>koma</strong> atau <strong>enter</strong>. Sistem akan otomatis membuat tiket untuk setiap nomor kursi yang Anda masukkan.
                <span class="block mt-1">Contoh: <code class="px-1 py-0.5 bg-blue-100 dark:bg-blue-900/30 rounded">A1, A2, A3</code> atau satu per baris.</span>
              </span>
            </p>
          </div>
          <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" id="seatCount">Jumlah kursi: <span class="font-semibold">0</span></p>
        </div>

        <!-- Active Status & Submit -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-gray-200/50 dark:border-gray-700/50">
          <label class="flex items-center gap-2 cursor-pointer group">
            <div class="relative">
              <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
              <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-500/60 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
            </div>
            <span class="text-sm font-medium group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">Aktifkan film (tampilkan di website)</span>
          </label>

          <button type="submit" class="btn-primary inline-flex items-center justify-center gap-2 px-6 py-3 shadow-lg hover:shadow-xl">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span class="font-semibold">Tambah Film</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Daftar Film -->
    <div class="glass-card overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
        <h2 class="text-xl font-semibold">Daftar Film Tersimpan</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total: {{ count($films) }} film</p>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50/50 dark:bg-gray-800/50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">ID</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Poster</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Judul</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Harga</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
            @forelse($films as $f)
            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
              <form method="POST" action="{{ route('admin.films.update') }}" class="contents">
                @csrf
                <input type="hidden" name="id" value="{{ $f->id }}">

                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                  #{{ $f->id }}
                </td>

                <td class="px-4 py-3">
                  @if($f->poster)
                    <img src="{{ asset($f->poster) }}" alt="{{ $f->title }}" class="w-16 h-24 object-cover rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                  @else
                    <div class="w-16 h-24 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-gray-200 dark:border-gray-700">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                    </div>
                  @endif
                </td>

                <td class="px-4 py-3">
                  <input type="text" name="title" value="{{ $f->title }}"
                    style="width: 100%; min-width: 200px; padding: 0.5rem 0.75rem; border: 2px solid #4b5563; border-radius: 0.5rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.875rem; font-weight: 500;">
                </td>

                <td class="px-4 py-3">
                  <div class="flex items-center gap-1">
                    <span class="text-sm font-medium text-gray-400">Rp</span>
                    <input type="number" name="price" value="{{ $f->price }}" step="1000" min="0"
                      style="width: 7rem; padding: 0.5rem 0.75rem; border: 2px solid #4b5563; border-radius: 0.5rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.875rem; font-weight: 500;">
                  </div>
                </td>

                <td class="px-4 py-3">
                  <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $f->is_active ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-500/60 dark:peer-focus:ring-red-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-green-600"></div>
                  </label>
                </td>

                <td class="px-4 py-3 space-x-2">
                  <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Update
                  </button>
              </form>
                  <form method="POST" action="{{ route('admin.films.delete', $f->id) }}" style="display:inline" onsubmit="return confirm('Hapus film ini? Semua tiket terkait akan dihapus!')">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-sm font-medium">
                      <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Hapus
                    </button>
                  </form>
                </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                </svg>
                <p class="text-lg font-semibold mb-1">Belum ada film</p>
                <p class="text-sm">Tambahkan film pertama menggunakan form di atas</p>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    // Poster Preview
    document.getElementById('coverInput')?.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Validate size (5MB)
        if (file.size > 5 * 1024 * 1024) {
          alert('Ukuran file terlalu besar! Maksimal 5 MB.');
          e.target.value = '';
          return;
        }

        // Validate type
        if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
          alert('Format file tidak didukung! Gunakan JPG atau PNG.');
          e.target.value = '';
          return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(event) {
          document.getElementById('previewImage').src = event.target.result;
          document.getElementById('posterPreview').classList.remove('hidden');
          document.getElementById('posterPlaceholder').classList.add('hidden');
        };
        reader.readAsDataURL(file);
      }
    });

    function clearPoster() {
      document.getElementById('coverInput').value = '';
      document.getElementById('posterPreview').classList.add('hidden');
      document.getElementById('posterPlaceholder').classList.remove('hidden');
    }

    // Format Price Display
    const priceInput = document.getElementById('priceInput');
    const priceFormatted = document.getElementById('priceFormatted');

    priceInput?.addEventListener('input', function() {
      const value = parseInt(this.value) || 0;
      if (value > 0) {
        const formatted = new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0
        }).format(value);
        priceFormatted.textContent = `≈ ${formatted}`;
        priceFormatted.classList.remove('text-gray-500');
        priceFormatted.classList.add('text-green-600', 'dark:text-green-400', 'font-semibold');
      } else {
        priceFormatted.textContent = 'Kelipatan Rp 1.000';
        priceFormatted.classList.add('text-gray-500');
        priceFormatted.classList.remove('text-green-600', 'dark:text-green-400', 'font-semibold');
      }
    });

    // Count Seats
    const seatsInput = document.getElementById('seatsInput');
    const seatCount = document.getElementById('seatCount');

    function updateSeatCount() {
      const text = seatsInput.value.trim();
      if (!text) {
        seatCount.innerHTML = 'Jumlah kursi: <span class="font-semibold text-gray-400">0</span>';
        return;
      }

      // Split by comma or newline
      const seats = text.split(/[\r\n,]+/)
        .map(s => s.trim())
        .filter(s => s.length > 0);

      const count = seats.length;
      const color = count > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-400';
      seatCount.innerHTML = `Jumlah kursi: <span class="font-semibold ${color}">${count}</span>`;

      if (count > 0) {
        seatCount.innerHTML += ` <span class="text-xs text-gray-500">• ${seats.slice(0, 3).join(', ')}${count > 3 ? '...' : ''}</span>`;
      }
    }

    seatsInput?.addEventListener('input', updateSeatCount);

    // Initial counts
    if (priceInput?.value) priceInput.dispatchEvent(new Event('input'));
    if (seatsInput?.value) updateSeatCount();

    // Force input text color for dark mode (always white)
    function forceInputColors() {
      const allInputs = document.querySelectorAll('.input-field');
      allInputs.forEach(input => {
        input.style.color = '#ffffff';
        input.style.WebkitTextFillColor = '#ffffff';
      });
    }

    // Apply on load
    forceInputColors();

    // Apply on any input added dynamically
    setTimeout(forceInputColors, 100);

    // Form validation before submit
    document.getElementById('filmForm')?.addEventListener('submit', function(e) {
      const title = document.querySelector('input[name="title"]').value.trim();
      const price = document.querySelector('input[name="price"]').value;

      if (!title) {
        e.preventDefault();
        alert('Judul film tidak boleh kosong!');
        document.querySelector('input[name="title"]').focus();
        return false;
      }

      if (!price || price <= 0) {
        e.preventDefault();
        alert('Harga tiket harus lebih dari 0!');
        document.querySelector('input[name="price"]').focus();
        return false;
      }

      // Show loading state
      const btn = this.querySelector('button[type="submit"]');
      btn.disabled = true;
      btn.innerHTML = `
        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        <span class="font-semibold">Menyimpan...</span>
      `;
    });
  </script>
</body>
</html>
