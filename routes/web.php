--- a/routes/web.php
+++ b/routes/web.php
@@ -4,9 +4,9 @@
 use App\Http\Controllers\ProfileController;
 use Illuminate\Support\Facades\Route;
 use App\Livewire\Instruktur\Index as InstrukturIndex;
-use App\Livewire\Kursus\Index as KursusIndex;
-use App\Livewire\Pendaftaran\Index as PendaftaranIndex;
-use App\Livewire\Report\PesertaPerKursus;
+// Menghapus alias untuk komponen Livewire untuk menggunakan namespace lengkap secara langsung dalam route
+// use App\Livewire\Kursus\Index as KursusIndex;
+// use App\Livewire\Pendaftaran\Index as PendaftaranIndex;
 
 Route::get('/', function () {
     return view('welcome');
@@ -19,9 +19,9 @@
     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
 
     // Livewire routes untuk CRUD
-    Route::get('/instruktur', InstrukturIndex::class)->name('instruktur.index');
-    Route::get('/kursus', KursusIndex::class)->name('kursus.index');
-    Route::get('/pendaftaran', PendaftaranIndex::class)->name('pendaftaran.index');
-    Route::get('/report/peserta-per-kursus', PesertaPerKursus::class)->name('report.peserta-per-kursus');
+    Route::get('/instruktur', \App\Livewire\Instruktur\Index::class)->name('instruktur.index');
+    Route::get('/kursus', \App\Livewire\Kursus\Index::class)->name('kursus.index');
+    Route::get('/pendaftaran', \App\Livewire\Pendaftaran\Index::class)->name('pendaftaran.index');
+    Route::get('/report/peserta-per-kursus', \App\Livewire\Report\PesertaPerKursus::class)->name('report.peserta-per-kursus');
 });
 
 require __DIR__.'/auth.php';