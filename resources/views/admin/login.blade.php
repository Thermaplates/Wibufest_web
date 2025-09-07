<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>body{min-height:100vh}</style>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      const input = document.getElementById('password');
      if(input){ input.focus(); }
    });
  </script>
  <script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>
</head>
<body class="bg-gray-100 flex items-center justify-center p-4">
  <div class="bg-white w-full max-w-sm rounded shadow p-6">
    <h1 class="text-xl font-bold mb-4">Login Admin</h1>

    @if(session('error'))
      <div class="mb-3 p-2 bg-red-100 text-red-700 rounded">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
      @csrf
      <label class="block text-sm font-medium mb-1" for="password">Password</label>
      <input id="password" name="password" type="password" class="w-full border rounded px-3 py-2 mb-3" required>

      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">Masuk</button>
    </form>
  </div>
</body>
</html>


