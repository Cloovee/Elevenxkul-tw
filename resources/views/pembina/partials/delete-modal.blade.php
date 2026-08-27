{{-- Modal konfirmasi hapus, dipakai bersama tombol [data-delete-form] --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#2E2B55]/40 backdrop-blur-sm px-4">
    <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-[0_20px_50px_-20px_rgba(46,43,85,0.45)]">
        <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-500 flex items-center justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
        </div>
        <h3 class="font-display text-lg font-semibold mb-1">Hapus data ini?</h3>
        <p id="delete-modal-text" class="text-sm text-inksoft mb-5">Tindakan ini tidak bisa dibatalkan.</p>
        <div class="flex gap-3">
            <button type="button" id="delete-modal-cancel"
                    class="flex-1 py-2.5 rounded-xl border border-[#E7E7F4] font-semibold text-sm text-inksoft hover:bg-bgsoft">Batal</button>
            <button type="button" id="delete-modal-confirm"
                    class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold text-sm">Ya, Hapus</button>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('delete-modal');
        const modalText = document.getElementById('delete-modal-text');
        const cancelBtn = document.getElementById('delete-modal-cancel');
        const confirmBtn = document.getElementById('delete-modal-confirm');
        let formToDelete = null;

        function openModal(form, message) {
            formToDelete = form;
            modalText.textContent = message || 'Tindakan ini tidak bisa dibatalkan.';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            formToDelete = null;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.querySelectorAll('[data-delete-form]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const form = document.getElementById(btn.dataset.deleteForm);
                if (form) openModal(form, btn.dataset.deleteMessage);
            });
        });

        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        confirmBtn.addEventListener('click', () => {
            if (formToDelete) formToDelete.submit();
        });
    })();
</script>