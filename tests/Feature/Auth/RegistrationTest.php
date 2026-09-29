<?php

// Registrasi publik dinonaktifkan: akun baru hanya dibuat oleh Pemilik/Admin melalui menu Pengguna.

test('registration screen is not available', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});

test('new users can not register themselves', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertNotFound();
    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
});
