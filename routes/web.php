<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\AdminAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Domain Configuration (Change these for local dev, e.g., 'localhost')
$mainDomain = env('MAIN_DOMAIN', 'saldo.com.co');
$appDomain = env('APP_DOMAIN', 'pay.saldo.com.co');
$adminDomain = env('ADMIN_DOMAIN', 'admin.saldo.com.co');

// -----------------------------------------------------------------------------
// 1. PUBLIC LANDING PAGE (saldo.com.co)
// -----------------------------------------------------------------------------
Route::domain($mainDomain)->group(function () {
    Route::get('/', function () {
        return redirect('/es-CO');
    })->name('home');

    Route::get('/sitemap.xml', function () {
        $locales = ['es-CO', 'es-AR', 'es-MX', 'es-ES', 'en-US'];
        $urls = [];

        foreach ($locales as $locale) {
            $urls[] = url('/' . $locale);
            $urls[] = url('/' . $locale . '/terminos');
            $urls[] = url('/' . $locale . '/privacidad');
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u, ENT_XML1) . "</loc>\n";
            $xml .= '    <lastmod>' . now()->toAtomString() . "</lastmod>\n";
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>\n";

        return response($xml, 200)->header('Content-Type', 'application/xml');
    });

    Route::get('/robots.txt', function () {
        $content = "User-agent: *\nAllow: /\n\nSitemap: " . url('/sitemap.xml') . "\n";
        return response($content, 200)->header('Content-Type', 'text/plain');
    });

    Route::pattern('locale', '^[a-z]{2}-[A-Z]{2}$');

    Route::middleware('locale.route')->group(function () {
        Route::get('/{locale}', function (string $locale) {
            $settings = app(\App\Services\SettingsService::class);
            $title = $settings->get('seo.home.' . $locale . '.title') ?? null;
            $description = $settings->get('seo.home.' . $locale . '.description') ?? null;
            $robots = $settings->get('seo.home.' . $locale . '.robots') ?? null;
            $keywords = $settings->get('seo.home.' . $locale . '.keywords') ?? null;

            return view('welcome', [
                'locale' => $locale,
                'seo' => [
                    'title' => $title,
                    'description' => $description,
                    'robots' => $robots,
                    'keywords' => $keywords,
                ],
            ]);
        })->name('home.locale');

        Route::get('/{locale}/terminos', function (string $locale) {
            return view('legal.terms', ['locale' => $locale]);
        })->name('legal.terms');

        Route::get('/{locale}/privacidad', function (string $locale) {
            return view('legal.privacy', ['locale' => $locale]);
        })->name('legal.privacy');
    });
});

// -----------------------------------------------------------------------------
// 2. USER WALLET APP (pay.saldo.com.co)
// -----------------------------------------------------------------------------
Route::domain($appDomain)->middleware('mobile.only')->group(function () {
    
    // Redirect root to login if not authenticated
    Route::get('/', function () {
        return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
        Route::get('/verify', [AuthController::class, 'showVerificationForm'])->name('verification.form');
        Route::post('/verify', [AuthController::class, 'verifyChallenge'])->name('verification.verify')->middleware('throttle:10,1');
        Route::post('/verify/resend', [AuthController::class, 'resendChallenge'])->name('verification.resend')->middleware('throttle:3,1');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // Dashboard & Wallet Operations (Protected)
    Route::middleware(['auth', 'session.version'])->group(function () {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            $wallets = $user->wallets;
            $walletIds = $wallets->pluck('id')->toArray();
            
            $transactions = \App\Models\Transaction::whereIn('sender_wallet_id', $walletIds)
                ->orWhereIn('receiver_wallet_id', $walletIds)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Get contacts with their user info
            $contacts = \App\Models\Contact::where('user_id', $user->id)
                ->with('contactUser')
                ->get();
                
            return view('dashboard', compact('user', 'wallets', 'transactions', 'contacts'));
        })->name('dashboard');
        
        // Wallet Operations
        Route::post('/transfer', [App\Http\Controllers\Web\WalletController::class, 'transfer'])->name('transfer');
        Route::post('/exchange', [App\Http\Controllers\Web\WalletController::class, 'exchange'])->name('exchange');
        Route::post('/verify-pin', [AuthController::class, 'verifyPin'])->name('verify-pin');
        
        // Contacts
        Route::post('/contacts', [App\Http\Controllers\Web\ContactController::class, 'store'])->name('contacts.store');
    });
});

// -----------------------------------------------------------------------------
// 3. ADMIN PANEL (admin.saldo.com.co)
// -----------------------------------------------------------------------------
Route::domain($adminDomain)->name('admin.')->group(function () {
    
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post')->middleware('throttle:10,1');
        Route::get('/verify', [AdminAuthController::class, 'showVerifyForm'])->name('verify.form');
        Route::post('/verify', [AdminAuthController::class, 'verify'])->name('verify.post')->middleware('throttle:10,1');
    });

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth:admin');

    Route::middleware(['auth:admin', 'admin.ip', 'session.version'])->group(function () {
        Route::get('/', [App\Http\Controllers\Web\AdminController::class, 'dashboard']);
        Route::get('/dashboard', [App\Http\Controllers\Web\AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [App\Http\Controllers\Web\AdminController::class, 'users'])->name('users');
        Route::get('/users/{id}', [App\Http\Controllers\Web\AdminController::class, 'showUser'])->name('users.show');
        Route::get('/users/{id}/edit', [App\Http\Controllers\Web\AdminController::class, 'editUser'])->name('users.edit');
        Route::post('/users/{id}', [App\Http\Controllers\Web\AdminController::class, 'updateUser'])->name('users.update');
        Route::post('/users/{id}/delete', [App\Http\Controllers\Web\AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/kyc', [App\Http\Controllers\Web\AdminController::class, 'kyc'])->name('kyc');
        Route::post('/kyc/{id}/approve', [App\Http\Controllers\Web\AdminController::class, 'approveKyc'])->name('kyc.approve');
        Route::post('/kyc/{id}/reject', [App\Http\Controllers\Web\AdminController::class, 'rejectKyc'])->name('kyc.reject');
        Route::get('/security', [App\Http\Controllers\Web\AdminController::class, 'security'])->name('security');
        Route::get('/settings/security', [App\Http\Controllers\Web\AdminController::class, 'securitySettings'])->name('settings.security');
        Route::get('/settings/integrations', [App\Http\Controllers\Web\AdminController::class, 'integrationsSettings'])->name('settings.integrations');
        Route::post('/settings/integrations', [App\Http\Controllers\Web\AdminController::class, 'saveIntegrations'])->name('settings.integrations.save');
        Route::post('/settings/integrations/test-email', [App\Http\Controllers\Web\AdminController::class, 'testEmail'])->name('settings.integrations.testEmail');
        Route::post('/settings/integrations/test-sms', [App\Http\Controllers\Web\AdminController::class, 'testSms'])->name('settings.integrations.testSms');
        Route::post('/settings/tools/clear-cache', [App\Http\Controllers\Web\AdminController::class, 'clearCaches'])->name('settings.tools.clearCache');
        Route::get('/settings/seo', [App\Http\Controllers\Web\AdminController::class, 'seoSettings'])->name('settings.seo');
        Route::post('/settings/seo', [App\Http\Controllers\Web\AdminController::class, 'saveSeo'])->name('settings.seo.save');
        Route::post('/settings/seo/favicon', [App\Http\Controllers\Web\AdminController::class, 'uploadFavicon'])->name('settings.seo.favicon');
        Route::post('/settings/2fa/totp/start', [App\Http\Controllers\Web\AdminController::class, 'startTotpSetup'])->name('settings.2fa.totp.start');
        Route::post('/settings/2fa/totp/verify', [App\Http\Controllers\Web\AdminController::class, 'verifyTotpSetup'])->name('settings.2fa.totp.verify');
        Route::post('/settings/2fa/disable', [App\Http\Controllers\Web\AdminController::class, 'disable2fa'])->name('settings.2fa.disable');
        Route::post('/settings/ip/enable', [App\Http\Controllers\Web\AdminController::class, 'enableIpAllowlist'])->name('settings.ip.enable');
        Route::post('/settings/ip/disable', [App\Http\Controllers\Web\AdminController::class, 'disableIpAllowlist'])->name('settings.ip.disable');
        Route::post('/settings/ip/add', [App\Http\Controllers\Web\AdminController::class, 'addAllowlistIp'])->name('settings.ip.add');
        Route::post('/settings/ip/{id}/delete', [App\Http\Controllers\Web\AdminController::class, 'deleteAllowlistIp'])->name('settings.ip.delete');
        Route::post('/settings/password', [App\Http\Controllers\Web\AdminController::class, 'changePassword'])->name('settings.password');
    });
});
