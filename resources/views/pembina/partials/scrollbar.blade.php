{{--
    Scrollbar khusus role PEMBINA — tipis, tanpa tombol panah, warna biru senada tema.
    Di-include otomatis lewat sidebar.blade.php, jadi berlaku di semua halaman pembina.

    Cara MENYEMBUNYIKAN total (tetap bisa scroll): hapus seluruh isi <style> di bawah,
    lalu ganti dengan:
        html, * { scrollbar-width: none; }
        html::-webkit-scrollbar, *::-webkit-scrollbar { display: none; }
--}}
<style>
    /* Chrome, Edge, Safari */
    html::-webkit-scrollbar,
    *::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }
    html::-webkit-scrollbar-track,
    *::-webkit-scrollbar-track {
        background: transparent;
    }
    html::-webkit-scrollbar-thumb,
    *::-webkit-scrollbar-thumb {
        background-color: rgba(11, 64, 156, .22);   /* biru tema, transparan */
        border-radius: 999px;
        border: 3px solid transparent;              /* bikin thumb tampak ramping */
        background-clip: content-box;
        transition: background-color .2s ease;
    }
    html::-webkit-scrollbar-thumb:hover,
    *::-webkit-scrollbar-thumb:hover {
        background-color: rgba(11, 64, 156, .45);
    }
    html::-webkit-scrollbar-button,
    *::-webkit-scrollbar-button {
        display: none;                              /* hilangkan panah atas/bawah */
        width: 0;
        height: 0;
    }
    html::-webkit-scrollbar-corner,
    *::-webkit-scrollbar-corner {
        background: transparent;
    }

    /* Firefox */
    @supports not selector(::-webkit-scrollbar) {
        html, * {
            scrollbar-width: thin;
            scrollbar-color: rgba(11, 64, 156, .3) transparent;
        }
    }
</style>
