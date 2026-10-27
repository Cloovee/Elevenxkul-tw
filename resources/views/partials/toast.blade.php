{{--
    Notifikasi (toast) pojok kanan bawah. Disisipkan otomatis ke semua halaman HTML oleh
    App\Http\Middleware\InjectToast -- TIDAK perlu di-@include manual di view.

    Isi:
      1. Menampilkan toast dari server ($toasts): error validasi/login, flash error/success/warning/status.
      2. Menangkap kolom form yang belum valid DI BROWSER (kolom kosong, email salah format,
         password kurang panjang, dst.) dan menjelaskannya dalam toast + menyorot kolomnya.
      3. Penanda "Password cocok / belum sama" otomatis di bawah kolom konfirmasi password.
      4. window.showToast({...}) bisa dipanggil dari JavaScript mana pun, mis. untuk fetch() yang gagal:
           showToast({ type: 'error', title: 'Gagal', message: 'Absensi tidak tersimpan.' })
         type: 'error' | 'success' | 'warning' | 'info'. Opsi lain: list (array teks), hint (teks kecil).

    CSS ditulis mentah (prefix .tst-) supaya tampil benar tanpa rebuild Tailwind.
--}}
<style>
    .tst-wrap{position:fixed;right:1.25rem;bottom:1.25rem;z-index:99999;display:flex;flex-direction:column;align-items:flex-end;gap:.65rem;
        width:min(25rem,calc(100vw - 2rem));pointer-events:none;font-family:'Plus Jakarta Sans','Instrument Sans',ui-sans-serif,system-ui,sans-serif}
    @media(max-width:520px){.tst-wrap{right:.75rem;left:.75rem;bottom:.75rem;width:auto}}

    .tst{--c:#0B409C;--soft:rgba(11,64,156,.1);position:relative;overflow:hidden;pointer-events:auto;width:100%;display:flex;gap:.8rem;align-items:flex-start;
        padding:.9rem 2.4rem .95rem .9rem;border-radius:1.1rem;background:#fff;color:#10316B;
        box-shadow:0 22px 44px -14px rgba(16,49,107,.42),0 0 0 1px rgba(16,49,107,.08);
        animation:tstIn .4s cubic-bezier(.16,1,.3,1) both}
    .tst::before{content:'';position:absolute;left:0;top:0;bottom:0;width:.28rem;background:var(--c)}
    .tst::after{content:'';position:absolute;left:0;right:0;bottom:0;height:.2rem;background:var(--c);opacity:.35;transform-origin:left;
        animation:tstBar var(--d,7s) linear forwards}
    .tst:hover::after{animation-play-state:paused}
    .tst.is-out{animation:tstOut .25s ease forwards}

    .tst-error{--c:#D92D20;--soft:#FEE4E2}
    .tst-success{--c:#12805C;--soft:#D1FADF}
    .tst-warning{--c:#B88700;--soft:#FFF1B8}
    .tst-warning::before{background:#FFE867}
    .tst-info{--c:#0B409C;--soft:rgba(11,64,156,.1)}

    .tst-ic{flex:none;width:2.1rem;height:2.1rem;border-radius:.8rem;background:var(--soft);color:var(--c);display:flex;align-items:center;justify-content:center;margin-left:.2rem}
    .tst-ic svg{width:1.1rem;height:1.1rem}
    .tst-body{min-width:0;flex:1}
    .tst-title{margin:0;font-size:.88rem;font-weight:800;line-height:1.3;letter-spacing:-.005em}
    .tst-msg{margin:.25rem 0 0;font-size:.8rem;line-height:1.5;color:#3d4f77;overflow-wrap:anywhere}
    .tst-list{margin:.4rem 0 0;padding:0;list-style:none;display:flex;flex-direction:column;gap:.25rem}
    .tst-list li{position:relative;padding-left:.95rem;font-size:.79rem;line-height:1.45;color:#10316B;font-weight:600;overflow-wrap:anywhere}
    .tst-list li::before{content:'';position:absolute;left:.1rem;top:.5em;width:.4rem;height:.4rem;border-radius:50%;background:var(--c)}
    .tst-hint{margin:.55rem 0 0;padding:.5rem .65rem;border-radius:.7rem;background:rgba(255,232,103,.45);font-size:.74rem;line-height:1.45;color:#10316B}
    .tst-hint b{font-weight:800}
    .tst-x{position:absolute;top:.55rem;right:.55rem;width:1.65rem;height:1.65rem;border:0;border-radius:50%;background:transparent;color:#5B6E96;cursor:pointer;
        display:flex;align-items:center;justify-content:center;transition:background .2s,color .2s}
    .tst-x:hover{background:rgba(16,49,107,.08);color:#10316B}
    .tst-x svg{width:.8rem;height:.8rem}

    @keyframes tstIn{from{opacity:0;transform:translateX(28px) scale(.97)}to{opacity:1;transform:none}}
    @keyframes tstOut{to{opacity:0;transform:translateX(28px) scale(.97)}}
    @keyframes tstBar{from{transform:scaleX(1)}to{transform:scaleX(0)}}

    /* kolom yang bermasalah disorot sampai pengguna mengubah isinya */
    .tst-invalid{outline:2px solid #D92D20 !important;outline-offset:1px;border-color:#D92D20 !important}

    /* penanda live di bawah kolom konfirmasi password */
    .tst-live{margin:.35rem 0 0;font-size:.74rem;font-weight:700}
    .tst-live.is-ok{color:#12805C}
    .tst-live.is-bad{color:#D92D20}

    @media(prefers-reduced-motion:reduce){.tst,.tst.is-out{animation-duration:.01s}.tst::after{animation:none}}
    @media print{.tst-wrap{display:none !important}}
</style>

<div class="tst-wrap" data-toast-root aria-live="polite"></div>

<script>
(function () {
    'use strict';

    var root = document.querySelector('[data-toast-root]');
    if (!root || window.showToast) return;

    var SERVER_TOASTS = @json($toasts ?? []);

    var ICON = {
        error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
        warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
    };
    var CLOSE = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
    var DURATION = { error: 11000, warning: 9000, success: 5500, info: 7000 };
    var MAX_VISIBLE = 4;

    function el(tag, cls, text) {
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (text != null) n.textContent = text;   // textContent = aman dari injeksi HTML
        return n;
    }

    function dismiss(t) {
        if (!t || t.__gone) return;
        t.__gone = true;
        t.classList.add('is-out');
        setTimeout(function () { if (t.parentNode) t.parentNode.removeChild(t); }, 260);
    }

    function showToast(o) {
        o = o || {};
        var type = ICON[o.type] ? o.type : 'info';
        var key = o.key || (type + '|' + (o.title || '') + '|' + (o.message || '') + '|' + (o.list || []).join('|'));

        // Toast yang sama persis tidak ditumpuk: yang lama diganti.
        Array.prototype.slice.call(root.children).forEach(function (c) { if (c.__key === key) dismiss(c); });

        var t = el('div', 'tst tst-' + type);
        t.__key = key;
        t.setAttribute('role', type === 'error' ? 'alert' : 'status');
        t.style.setProperty('--d', (DURATION[type] + (o.list ? o.list.length * 1200 : 0)) + 'ms');

        var ic = el('span', 'tst-ic');
        ic.innerHTML = ICON[type];               // ikon statis milik kita sendiri
        t.appendChild(ic);

        var body = el('div', 'tst-body');
        if (o.title)   body.appendChild(el('p', 'tst-title', o.title));
        if (o.message) body.appendChild(el('p', 'tst-msg', o.message));
        if (o.list && o.list.length) {
            var ul = el('ul', 'tst-list');
            o.list.forEach(function (s) { ul.appendChild(el('li', null, s)); });
            body.appendChild(ul);
        }
        if (o.hint) {
            var h = el('p', 'tst-hint');
            h.appendChild(el('b', null, 'Petunjuk: '));
            h.appendChild(document.createTextNode(o.hint));
            body.appendChild(h);
        }
        t.appendChild(body);

        var x = el('button', 'tst-x');
        x.type = 'button';
        x.setAttribute('aria-label', 'Tutup notifikasi');
        x.innerHTML = CLOSE;
        x.addEventListener('click', function () { dismiss(t); });
        t.appendChild(x);

        // Hilang sendiri setelah progress bar habis (berhenti sementara saat kursor di atasnya).
        t.addEventListener('animationend', function (e) { if (e.animationName === 'tstBar') dismiss(t); });

        root.appendChild(t);
        while (root.children.length > MAX_VISIBLE) dismiss(root.firstElementChild);
        return t;
    }
    window.showToast = showToast;

    /* ---------- 1. Toast dari server ---------- */
    function sembunyikanBannerKembar(teks) {
        // Banner flash bawaan halaman yang isinya sama persis dengan toast disembunyikan, supaya tidak dobel.
        var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null);
        var n, kena = [];
        while ((n = walker.nextNode())) {
            if (n.nodeValue.replace(/\s+/g, ' ').trim() === teks && !root.contains(n)) kena.push(n.parentElement);
        }
        kena.forEach(function (p) {
            while (p.parentElement && p.parentElement !== document.body &&
                   p.parentElement.textContent.replace(/\s+/g, ' ').trim() === teks) p = p.parentElement;
            if (p && p !== document.body && !root.contains(p) && p.tagName !== 'MAIN') p.style.display = 'none';
        });
    }

    function mulai() {
        SERVER_TOASTS.forEach(function (o, i) {
            setTimeout(function () { showToast(o); }, 150 + i * 160);
            if (o.key && o.key.indexOf('flash:') === 0 && o.message) {
                sembunyikanBannerKembar(o.message.replace(/\s+/g, ' ').trim());
            }
        });
        pasangPenandaPassword();
    }

    /* ---------- 2. Kolom form yang belum valid (validasi browser) ---------- */
    var antrean = [], timer = null;

    function labelDari(f) {
        var t = '';
        if (f.id) {
            var l = document.querySelector('label[for="' + (window.CSS && CSS.escape ? CSS.escape(f.id) : f.id) + '"]');
            if (l) t = l.textContent;
        }
        var p = f.parentElement;
        for (var i = 0; !t && p && i < 5; i++, p = p.parentElement) {
            var lb = p.querySelector('label');
            if (lb) t = lb.textContent;
        }
        t = (t || f.getAttribute('aria-label') || f.getAttribute('placeholder') || f.name || 'Kolom ini');
        return t.replace(/[*:]/g, ' ').replace(/\s+/g, ' ').trim();
    }

    function pesanDari(f) {
        var v = f.validity, nama = labelDari(f);
        if (v.valueMissing) {
            if (f.type === 'checkbox') return nama + ' harus dicentang.';
            if (f.type === 'file') return 'Pilih file untuk ' + nama + '.';
            if (f.tagName === 'SELECT') return 'Pilih salah satu pada ' + nama + '.';
            return nama + ' wajib diisi.';
        }
        if (v.typeMismatch) {
            return f.type === 'email'
                ? nama + ' harus berupa email yang valid, contoh: nama@sekolah.sch.id.'
                : 'Isian ' + nama + ' formatnya belum sesuai.';
        }
        if (v.tooShort)  return nama + ' minimal ' + f.minLength + ' karakter (sekarang baru ' + f.value.length + ').';
        if (v.tooLong)   return nama + ' maksimal ' + f.maxLength + ' karakter.';
        if (v.patternMismatch) return f.title ? nama + ': ' + f.title + '.' : 'Format ' + nama + ' belum sesuai.';
        if (v.rangeUnderflow) return nama + ' minimal bernilai ' + f.min + '.';
        if (v.rangeOverflow)  return nama + ' maksimal bernilai ' + f.max + '.';
        return f.validationMessage ? nama + ': ' + f.validationMessage : 'Isian ' + nama + ' tidak valid.';
    }

    function terlihat(f) { return !!(f.offsetWidth || f.offsetHeight || f.getClientRects().length); }

    function tampilkanInvalid() {
        timer = null;
        var daftar = antrean; antrean = [];
        var pesan = [], pertama = null, tersembunyi = false;

        daftar.forEach(function (f) {
            var m = pesanDari(f);
            if (!terlihat(f)) { tersembunyi = true; }
            else { f.classList.add('tst-invalid'); if (!pertama) pertama = f; }
            if (pesan.indexOf(m) === -1) pesan.push(m);
        });

        var tampil = pesan.slice(0, 5);
        if (pesan.length > 5) tampil.push('...dan ' + (pesan.length - 5) + ' kolom lainnya.');

        showToast({
            type: 'error',
            title: 'Data belum valid',
            message: pesan.length === 1
                ? 'Salah satu data yang kamu isi belum benar:'
                : 'Beberapa data belum benar. Mohon isi dengan data yang valid:',
            list: tampil,
            hint: tersembunyi ? 'Ada kolom yang sedang tersembunyi. Pastikan pilihan di atasnya (mis. ekskul dan siswa) sudah lengkap.' : null,
            key: 'invalid-browser'
        });

        if (pertama) {
            try { pertama.focus({ preventScroll: true }); } catch (e) {}
            pertama.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // 'invalid' tidak menggelembung, jadi ditangkap di fase capture.
    document.addEventListener('invalid', function (e) {
        var f = e.target;
        if (!f || !f.form) return;
        e.preventDefault();                       // gantikan gelembung bawaan browser yang gampang terlewat
        antrean.push(f);
        if (!timer) timer = setTimeout(tampilkanInvalid, 0);
    }, true);

    function bersihkanSorotan(e) {
        if (e.target && e.target.classList) e.target.classList.remove('tst-invalid');
    }
    document.addEventListener('input', bersihkanSorotan, true);
    document.addEventListener('change', bersihkanSorotan, true);

    /* ---------- 3. Penanda "password cocok" di bawah kolom konfirmasi ---------- */
    function pasangPenandaPassword() {
        document.querySelectorAll('input[name="password_confirmation"]').forEach(function (c) {
            var form = c.form, p = form && form.querySelector('input[name="password"]');
            if (!p || c.__tstLive) return;
            var note = el('p', 'tst-live');
            note.hidden = true;
            c.insertAdjacentElement('afterend', note);
            c.__tstLive = true;
            function perbarui() {
                if (!c.value) { note.hidden = true; return; }
                var ok = c.value === p.value;
                note.hidden = false;
                note.className = 'tst-live ' + (ok ? 'is-ok' : 'is-bad');
                note.textContent = ok ? 'Password cocok.' : 'Belum sama dengan password di atas.';
            }
            c.addEventListener('input', perbarui);
            p.addEventListener('input', perbarui);
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', mulai);
    else mulai();
})();
</script>
