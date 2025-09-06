<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class AdminKey
{
    public function handle(Request $request, Closure $next)
    {
        // jika session sudah ter-set, lanjut
        if ($request->session()->get('is_admin')) {
            return $next($request);
        }

        // cek form post login
        if ($request->isMethod('post')) {
            $key = $request->input('admin_key');
            if ($key && $key === env('ADMIN_KEY')) {
                $request->session()->put('is_admin', true);
                return redirect()->to('/admin');
            }
        }

        // tampilkan form login admin kalau bukan POST
        return response()->view('admin.login'); // blade: resources/views/admin/login.blade.php
    }
}
