<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\RoleUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Kirim kode OTP ke email user
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = RoleUsers::where('email', $request->email)->first();

        // Selalu return success agar tidak bocorkan info user terdaftar atau tidak
        if (!$user) {
            return response()->json([
                'success' => true,
                'message' => 'Jika email terdaftar, kode verifikasi akan dikirim.',
            ]);
        }

        // Hapus OTP lama untuk email ini
        DB::table('password_reset_otps')->where('email', $request->email)->delete();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan OTP ke database (expired 10 menit)
        DB::table('password_reset_otps')->insert([
            'email'      => $request->email,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'used'       => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Kirim email
        try {
            Mail::to($request->email)->send(new PasswordResetOtpMail($otp, $user->name));
            Log::info('Password reset OTP sent', ['email' => $request->email]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email. Periksa koneksi internet atau coba lagi.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode verifikasi telah dikirim ke email Anda.',
        ]);
    }

    /**
     * Verifikasi kode OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $record = DB::table('password_reset_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Kode verifikasi tidak valid atau sudah kedaluwarsa.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Kode verifikasi valid.',
        ]);
    }

    /**
     * Reset password dengan OTP yang sudah diverifikasi
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'            => 'required|email',
            'otp'              => 'required|string|size:6',
            'new_pin'          => 'required|string|min:6|max:20',
            'new_pin_confirm'  => 'required|string|same:new_pin',
        ], [
            'new_pin.min'          => 'PIN baru minimal 6 karakter.',
            'new_pin_confirm.same' => 'Konfirmasi PIN tidak cocok.',
        ]);

        // Verifikasi OTP sekali lagi
        $record = DB::table('password_reset_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi verifikasi tidak valid atau sudah kedaluwarsa. Silakan mulai ulang.',
            ], 422);
        }

        $user = RoleUsers::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan.',
            ], 404);
        }

        // Update password (PIN)
        // Model RoleUsers memiliki mutator setPinAttribute yang akan hash otomatis
        $user->pin = $request->new_pin;
        $user->save();

        // Tandai OTP sebagai sudah digunakan
        DB::table('password_reset_otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->update(['used' => true]);

        Log::info('Password reset successful', ['email' => $request->email]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset! Silakan login dengan password baru Anda.',
        ]);
    }
}
