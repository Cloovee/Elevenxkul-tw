{{-- Gaya halaman Kelola Galeri (prefix .gl-). Ditulis mentah supaya tidak bergantung pada hasil `npm run build`. --}}
<style>
    .gl-panel{background:#fff;border:1px solid #EEF2FA;border-radius:1.5rem;box-shadow:0 4px 24px -8px rgba(0,0,0,.06);padding:1.5rem}
    .gl-head{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:1.25rem}
    .gl-h{font-size:1.25rem;font-weight:800;color:#10316B;margin:0}
    .gl-sub{font-size:.78rem;color:#7C8DB5;margin:.25rem 0 0;max-width:60ch}
    .gl-tools{display:flex;flex-wrap:wrap;align-items:center;gap:.5rem}
    .gl-search{height:2.5rem;width:14rem;max-width:100%;box-sizing:border-box;padding:0 1rem;border:0;border-radius:999px;background:#F2F7FF;color:#10316B;font:inherit;font-size:.85rem;outline:none}
    .gl-search:focus{box-shadow:0 0 0 3px rgba(11,64,156,.18)}
    .gl-btn{display:inline-flex;align-items:center;justify-content:center;gap:.45rem;height:2.5rem;padding:0 1.1rem;border:0;border-radius:999px;font:inherit;font-size:.82rem;font-weight:700;text-decoration:none;cursor:pointer;white-space:nowrap;transition:background .15s,color .15s,opacity .15s}
    .gl-btn-primary{background:#0B409C;color:#fff;box-shadow:0 8px 18px -8px rgba(11,64,156,.6)}
    .gl-btn-primary:hover{background:#09357F}
    .gl-btn-soft{background:#F2F7FF;color:#10316B}.gl-btn-soft:hover{background:#DDE8FB}
    .gl-btn-sm{height:2.1rem;padding:0 .9rem;font-size:.75rem}

    /* ---- grup per ekskul ---- */
    .gl-group{border:1px solid #E3ECFB;border-radius:1.25rem;padding:1.1rem 1.1rem 1.25rem;margin-top:1.1rem;background:#FCFDFF}
    .gl-ghead{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:.75rem;margin-bottom:.9rem}
    .gl-gname{display:flex;align-items:center;gap:.65rem;min-width:0}
    .gl-gico{width:2.4rem;height:2.4rem;flex:none;border-radius:.8rem;background:#0B409C;color:#fff;display:grid;place-items:center;font-size:.95rem}
    .gl-gtitle{font-size:1.05rem;font-weight:800;color:#10316B;margin:0;line-height:1.2}
    .gl-gcount{font-size:.74rem;color:#7C8DB5;margin:.1rem 0 0}
    .gl-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(190px,1fr));gap:.9rem}
    .gl-item{border:1px solid #E3ECFB;border-radius:1rem;overflow:hidden;background:#fff}
    .gl-img{position:relative;aspect-ratio:4/3;background:#F2F7FF;overflow:hidden}
    .gl-img img{width:100%;height:100%;object-fit:cover;display:block}
    .gl-no{position:absolute;top:.5rem;left:.5rem;padding:.15rem .55rem;border-radius:999px;background:rgba(16,49,107,.85);color:#fff;font-size:.68rem;font-weight:700}
    .gl-meta{padding:.65rem .75rem .75rem}
    .gl-title{font-size:.82rem;font-weight:700;color:#10316B;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .gl-ket{font-size:.72rem;color:#7C8DB5;margin:.2rem 0 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
    .gl-act{display:flex;gap:.4rem;margin-top:.6rem}
    .gl-act form{flex:1;margin:0}
    .gl-mini{display:flex;align-items:center;justify-content:center;gap:.35rem;width:100%;height:2rem;border:0;border-radius:.65rem;font:inherit;font-size:.72rem;font-weight:700;text-decoration:none;cursor:pointer;flex:1;transition:background .15s,color .15s}
    .gl-edit{background:#FFF7E0;color:#B7791F}.gl-edit:hover{background:#F59E0B;color:#fff}
    .gl-del{background:#FEECEC;color:#DC2626}.gl-del:hover{background:#DC2626;color:#fff}
    .gl-empty{display:flex;flex-direction:column;align-items:center;gap:.25rem;padding:1.6rem 1rem;border:1.5px dashed #C9D6F0;border-radius:1rem;color:#7C8DB5;font-size:.82rem;text-align:center;background:#fff}
    .gl-empty i{font-size:1.4rem;margin-bottom:.2rem}
    .gl-note{display:flex;gap:.6rem;align-items:flex-start;padding:.8rem 1rem;border-radius:1rem;background:#FFF7E0;color:#8A5A00;font-size:.8rem;margin-top:1.1rem}

    /* ---- form ---- */
    .gl-form{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem}
    .gl-full{grid-column:1/-1}
    .gl-label{display:block;font-size:.72rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:#2b3674;margin-bottom:.45rem}
    .gl-label b{color:#DC2626}
    .gl-input{display:block;width:100%;box-sizing:border-box;padding:.75rem 1rem;border:1.5px solid transparent;border-radius:.85rem;background:#F4F7FE;color:#2b3674;font:inherit;font-size:.9rem;outline:none;transition:border-color .15s,background .15s,box-shadow .15s}
    .gl-input:focus{background:#fff;border-color:#0B409C;box-shadow:0 0 0 4px rgba(11,64,156,.12)}
    .gl-input.err{border-color:#EF4444}
    textarea.gl-input{resize:vertical;min-height:5.5rem}
    .gl-hint{font-size:.74rem;color:#8E9AC0;margin:.4rem 0 0}
    .gl-error{font-size:.76rem;color:#EF4444;font-weight:600;margin:.4rem 0 0}
    .gl-drop{display:flex;flex-direction:column;align-items:center;gap:.35rem;padding:1.6rem 1rem;border:2px dashed #B9C8E6;border-radius:1.1rem;background:#F7FAFF;color:#5B6E96;text-align:center;cursor:pointer;transition:border-color .15s,background .15s}
    .gl-drop:hover,.gl-drop.on{border-color:#0B409C;background:#EEF4FF}
    .gl-drop i{font-size:1.6rem;color:#0B409C}
    .gl-drop input{display:none}
    .gl-prev{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:.6rem;margin-top:.8rem}
    .gl-prev img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:.7rem;border:1px solid #E3ECFB}
    .gl-cur{display:block;width:100%;max-width:24rem;aspect-ratio:4/3;object-fit:cover;border-radius:1rem;border:1px solid #E3ECFB;margin-bottom:.8rem}
    .gl-foot{display:flex;flex-wrap:wrap;justify-content:flex-end;gap:.6rem;margin-top:1.75rem;padding-top:1.25rem;border-top:1px solid #EEF2FA}
    @media(max-width:640px){.gl-form{grid-template-columns:1fr}.gl-panel{padding:1.1rem}.gl-grid{grid-template-columns:repeat(2,1fr)}}
</style>
