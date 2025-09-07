<nav class="bg-white border-b border-gray-200">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between h-16">
      <div class="flex items-center space-x-4">
        <a href="{{ url('/') }}" class="text-xl font-bold text-red-600">Wibufest</a>
        <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900">Home</a>
        <a href="{{ url('/films') }}" class="text-gray-600 hover:text-gray-900">Films</a>
      </div>

      <div class="flex items-center space-x-4">
        <a href="{{ url('/admin') }}" class="text-sm px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Admin</a>
      </div>
    </div>
  </div>
</nav>