public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return match ($request->user()->role) {
            'admin' => redirect()->intended(route('dashboard.admin', absolute: false)),
            'pembina' => redirect()->intended(route('dashboard.pembina', absolute: false)),
            'ketua' => redirect()->intended(route('dashboard.ketua', absolute: false)),
            default => redirect()->intended(route('dashboard', absolute: false)),
        };
    }