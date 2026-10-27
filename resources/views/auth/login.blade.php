<x-guest-login-layout title="Login">
    {{--
        Gaya halaman login ditulis mentah (prefix .lg-) supaya tampilan TIDAK bergantung pada
        hasil `npm run build` -- class Tailwind baru sering belum ikut ter-build dan bikin layout berantakan.
    --}}
    <style>
        .lg-title { font-family: 'Fredoka', sans-serif; font-weight: 700; font-size: 1.875rem; line-height: 1.15; color: #10316B; margin: 0 0 .375rem; }
        .lg-sub   { color: #5B6E96; font-size: .95rem; line-height: 1.5; margin: 0 0 1.75rem; }

        .lg-card  { background: #fff; border: 1px solid rgba(16,49,107,.08); border-radius: 1.5rem; padding: 1.75rem;
                    box-shadow: 0 2px 24px -4px rgba(16,49,107,.10); }
        .lg-form  { display: flex; flex-direction: column; gap: 1.25rem; margin: 0; }

        .lg-label { display: block; font-size: .875rem; font-weight: 600; color: #10316B; margin-bottom: .5rem; }
        .lg-field { position: relative; }
        .lg-ico   { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 1.25rem; height: 1.25rem; color: #7C8CB0; pointer-events: none; }
        .lg-input { display: block; width: 100%; box-sizing: border-box; height: 3.25rem; padding: 0 1rem 0 2.9rem;
                    font: inherit; font-size: .95rem; color: #10316B; background: #F7FAFF;
                    border: 1.5px solid #D6E0F2; border-radius: .875rem; outline: none; box-shadow: none;
                    transition: border-color .15s, background-color .15s, box-shadow .15s; }
        .lg-input.has-eye { padding-right: 3.25rem; }
        .lg-input::placeholder { color: #9AA8C4; }
        .lg-input:hover { border-color: #B9C8E6; }
        .lg-input:focus { background: #fff; border-color: #0B409C; box-shadow: 0 0 0 4px rgba(11,64,156,.12); }
        .lg-field:focus-within .lg-ico { color: #0B409C; }

        .lg-eye   { position: absolute; right: .5rem; top: 50%; transform: translateY(-50%); width: 2.25rem; height: 2.25rem;
                    display: flex; align-items: center; justify-content: center; border: 0; background: transparent;
                    color: #7C8CB0; border-radius: .625rem; cursor: pointer; transition: color .15s, background-color .15s; }
        .lg-eye:hover { color: #0B409C; background: rgba(11,64,156,.07); }
        .lg-eye:focus-visible { outline: 2px solid #0B409C; outline-offset: 1px; }
        .lg-eye svg { width: 1.25rem; height: 1.25rem; }

        .lg-err ul { list-style: none; margin: .5rem 0 0; padding: 0; font-size: .85rem; color: #DC2626; }

        .lg-remember { display: inline-flex; align-items: center; gap: .625rem; cursor: pointer; font-size: .875rem; color: #5B6E96; width: fit-content; }
        .lg-remember input { width: 1.125rem; height: 1.125rem; margin: 0; flex-shrink: 0; accent-color: #0B409C; cursor: pointer; }

        .lg-btn   { display: flex; align-items: center; justify-content: center; gap: .5rem; width: 100%; box-sizing: border-box;
                    height: 3.25rem; border-radius: .875rem; font: inherit; font-size: .95rem; font-weight: 600; text-decoration: none;
                    cursor: pointer; transition: background-color .15s, border-color .15s, box-shadow .15s, transform .15s; }
        .lg-btn:focus-visible { outline: 3px solid rgba(11,64,156,.35); outline-offset: 2px; }
        .lg-btn-primary { border: 0; background: #0B409C; color: #fff; box-shadow: 0 8px 20px -8px rgba(11,64,156,.55); }
        .lg-btn-primary:hover { background: #09357F; transform: translateY(-1px); box-shadow: 0 12px 24px -8px rgba(11,64,156,.6); }
        .lg-btn-primary:active { transform: translateY(0); }

        .lg-or    { display: flex; align-items: center; gap: .875rem; margin: 1.25rem 0; font-size: .8rem; color: #8A98B8; }
        .lg-or::before, .lg-or::after { content: ""; flex: 1; height: 1px; background: rgba(16,49,107,.12); }

        .lg-btn-ghost { height: 3rem; background: #fff; color: #0B409C; border: 1.5px solid #C9D6F0; }
        .lg-btn-ghost:hover { background: #F2F7FF; border-color: #0B409C; }
        .lg-btn-ghost svg { width: 1.125rem; height: 1.125rem; transition: transform .15s; }
        .lg-btn-ghost:hover svg { transform: translateX(-3px); }

        /* ===== Info lupa kata sandi: pojok kanan bawah layar ===== */
        .lg-info      { position: fixed; right: 1.25rem; bottom: 1.25rem; z-index: 50; display: flex; flex-direction: column; align-items: flex-end; gap: .75rem; }
        .lg-info-btn  { width: 2.75rem; height: 2.75rem; border: 0; border-radius: 9999px; background: #0B409C; color: #fff; cursor: pointer;
                        display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px -6px rgba(11,64,156,.6);
                        transition: background-color .15s, transform .15s; }
        .lg-info-btn:hover { background: #09357F; transform: scale(1.06); }
        .lg-info-btn:focus-visible { outline: 3px solid rgba(255,255,255,.9); outline-offset: 2px; }
        .lg-info-btn svg { width: 1.5rem; height: 1.5rem; }
        .lg-info-box  { width: 18rem; max-width: calc(100vw - 2.5rem); box-sizing: border-box; padding: 1rem 1.125rem; border-radius: 1rem;
                        background: #fff; color: #10316B; font-size: .875rem; line-height: 1.55;
                        box-shadow: 0 20px 40px -12px rgba(0,0,0,.3); border: 1px solid rgba(16,49,107,.1); }
        .lg-info-box[hidden] { display: none; }
        .lg-info-box p { margin: 0; }
        .lg-info-box .lg-info-h { font-weight: 700; margin-bottom: .25rem; }
        .lg-info-box .lg-info-t { color: #5B6E96; }

        @media (max-width: 400px) { .lg-card { padding: 1.25rem; } }
    </style>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h2 class="lg-title">Masuk</h2>
    <p class="lg-sub">Masukkan email/username dan kata sandi akunmu.</p>

    <div class="lg-card">
        <form method="POST" action="{{ route('login') }}" class="lg-form">
            @csrf

            <!-- Email / Username -->
            <div>
                <label for="email" class="lg-label">Email / Username</label>
                <div class="lg-field">
                    <svg class="lg-ico" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l8.4 6a1 1 0 001.2 0L21 7.5M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input
                        id="email"
                        type="text"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Email atau username"
                        class="lg-input"
                    />
                </div>
                <div class="lg-err"><x-input-error :messages="$errors->get('email')" /></div>
            </div>

            <!-- Kata Sandi -->
            <div>
                <label for="password" class="lg-label">Kata Sandi</label>
                <div class="lg-field">
                    <svg class="lg-ico" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-12v3H8V7a4 4 0 118 0z" />
                    </svg>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan kata sandi"
                        class="lg-input has-eye"
                    />
                    <button type="button" onclick="togglePassword()" class="lg-eye" aria-label="Tampilkan / sembunyikan kata sandi">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="lg-err"><x-input-error :messages="$errors->get('password')" /></div>
            </div>

            <!-- Ingat saya -->
            <label for="remember_me" class="lg-remember">
                <input id="remember_me" type="checkbox" name="remember" />
                <span>Ingat saya</span>
            </label>

            <!-- Tombol Masuk -->
            <button type="submit" class="lg-btn lg-btn-primary">Masuk</button>
        </form>
    </div>

    <div class="lg-or"><span>atau</span></div>

    <!-- Kembali ke landing page publik -->
    <a href="{{ route('landing') }}" class="lg-btn lg-btn-ghost">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Beranda
    </a>

    {{-- Info lupa kata sandi (reset dilakukan oleh Admin, jadi tidak ada fitur "Lupa kata sandi"). --}}
    <div id="infoSandi" class="lg-info">
        <div id="infoSandiBox" role="tooltip" class="lg-info-box" hidden>
            <p class="lg-info-h">Lupa kata sandi?</p>
            <p class="lg-info-t">Silakan temui <strong>Admin</strong> untuk mengganti kata sandimu. Kata sandi akan direset langsung oleh Admin.</p>
        </div>
        <button id="infoSandiBtn" type="button" class="lg-info-btn" aria-label="Info lupa kata sandi" aria-expanded="false" aria-controls="infoSandiBox">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v5" />
                <circle cx="12" cy="7.75" r="0.6" fill="currentColor" />
            </svg>
        </button>
    </div>

    <script>
        (function () {
            const wrap = document.getElementById('infoSandi');
            const btn = document.getElementById('infoSandiBtn');
            const box = document.getElementById('infoSandiBox');

            function toggle(show) {
                box.hidden = !show;
                btn.setAttribute('aria-expanded', show ? 'true' : 'false');
            }

            btn.addEventListener('click', () => toggle(box.hidden));
            document.addEventListener('click', (e) => { if (!wrap.contains(e.target)) toggle(false); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') toggle(false); });
        })();
    </script>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"
                    />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                    />
                `;
            }
        }
    </script>
</x-guest-login-layout>