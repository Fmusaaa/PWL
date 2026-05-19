<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah sudah login
        if (!session()->has('isLoggedIn')) {
            return redirect()->to(site_url('login'));
        }

        // Cek role jika ada argumen, contoh: ['filter' => 'auth:admin']
        if (!empty($arguments)) {
            $allowedRole = $arguments[0]; // misal 'admin'
            if (session()->get('role') !== $allowedRole) {
                session()->setFlashdata('failed', 'Akses ditolak! Halaman ini hanya untuk ' . ucfirst($allowedRole) . '.');
                return redirect()->to(site_url('/'));
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}