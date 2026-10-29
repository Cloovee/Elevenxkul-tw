<?php

namespace Tests\Feature;

use App\Support\PublicStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Menjamin gambar upload tetap bisa dibuka walaupun symlink public/storage
 * tidak ada (kondisi yang terjadi setelah update di hosting).
 */
class PublicStorageTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->path = UploadedFile::fake()->image('poster.jpg')->store('poster-ekskul', 'public');
    }

    public function test_media_menyajikan_gambar_tanpa_symlink(): void
    {
        $this->get('/media?p=' . $this->path)
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_url_lama_storage_tetap_bisa_dibuka(): void
    {
        $this->get('/storage/' . $this->path)->assertOk();
    }

    public function test_url_memakai_jalur_media_kalau_file_tidak_ada_di_document_root(): void
    {
        $this->assertStringStartsWith('/media?p=poster-ekskul/', PublicStorage::url($this->path));
    }

    public function test_url_kosong_dan_link_eksternal(): void
    {
        $this->assertNull(PublicStorage::url(null));
        $this->assertNull(PublicStorage::url(''));
        $this->assertSame('https://a.test/x.jpg', PublicStorage::url('https://a.test/x.jpg'));
    }

    public function test_path_traversal_dan_file_tersembunyi_ditolak(): void
    {
        foreach (['../../.env', '..%2F..%2F.env', '.gitignore', '/etc/passwd', ''] as $bad) {
            $this->get('/media?p=' . $bad)->assertNotFound();
        }
    }

    public function test_file_bukan_gambar_ditolak(): void
    {
        Storage::disk('public')->put('dokumen/rahasia.txt', 'x');
        Storage::disk('public')->put('dokumen/x.php', '<?php echo 1;');
        Storage::disk('public')->put('dokumen/x.svg', '<svg onload="alert(1)"/>');

        foreach (['rahasia.txt', 'x.php', 'x.svg'] as $nama) {
            $this->get('/media?p=dokumen/' . $nama)->assertNotFound();
        }
    }

    public function test_gambar_tidak_memasang_cookie_session(): void
    {
        $this->get('/media?p=' . $this->path)->assertCookieMissing(config('session.cookie'));
    }
}
