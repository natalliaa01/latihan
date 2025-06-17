<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Manajemen Kursus
                </h2>

                <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    Tambah Kursus
                </button>

                @if (session()->has('message'))
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm">{{ session('message') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <table class="table-auto w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Nama Kursus</th>
                            <th class="py-3 px-6 text-left">Durasi</th>
                            <th class="py-3 px-6 text-left">Instruktur</th>
                            <th class="py-3 px-6 text-left">Biaya</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 dark:text-gray-400 text-sm font-light">
                        @foreach($courses as $course)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $course->nama_kursus }}</td>
                                <td class="py-3 px-6 text-left">{{ $course->durasi }}</td>
                                <td class="py-3 px-6 text-left">{{ $course->instruktur->nama ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">Rp{{ number_format($course->biaya, 0, ',', '.') }}</td>
                                <td class="py-3 px-6 text-center">
                                    <button wire:click="edit({{ $course->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs">Edit</button>
                                    <button wire:click="delete({{ $course->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs ml-2">Hapus</button>
                                    <button wire:click="openMateriModal({{ $course->id }})" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-2 rounded text-xs ml-2">Materi</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Modal Tambah/Edit Kursus --}}
                @if($isOpen)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ $course_id ? 'Edit Kursus' : 'Tambah Kursus' }}
                            </h3>
                            <form wire:submit.prevent="store">
                                <div class="mb-4">
                                    <label for="nama_kursus" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nama Kursus:</label>
                                    <input type="text" id="nama_kursus" wire:model="nama_kursus" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('nama_kursus') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="durasi" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Durasi:</label>
                                    <input type="text" id="durasi" wire:model="durasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: 3 bulan, 40 jam">
                                    @error('durasi') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="instruktur_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Instruktur:</label>
                                    <select id="instruktur_id" wire:model="instruktur_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Pilih Instruktur</option>
                                        @foreach($instructors as $instruktur)
                                            <option value="{{ $instruktur->id }}">{{ $instruktur->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('instruktur_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="biaya" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Biaya:</label>
                                    <input type="number" step="0.01" id="biaya" wire:model="biaya" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('biaya') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" wire:click="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Modal Upload Materi --}}
                @if($showMateriModal)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Upload Materi Kursus
                            </h3>
                            <form wire:submit.prevent="uploadMateri">
                                <div class="mb-4">
                                    <label for="materi_judul" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Judul Materi:</label>
                                    <input type="text" id="materi_judul" wire:model="materi_judul" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('materi_judul') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="materi_deskripsi" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Deskripsi (Opsional):</label>
                                    <textarea id="materi_deskripsi" wire:model="materi_deskripsi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="materi_file" class="block w-full text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">File Materi (PDF, Doc, Gambar, Max 10MB):</label>
                                    <input type="file" id="materi_file" wire:model="materi_file" class="block w-full text-sm text-gray-700
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-violet-50 file:text-violet-700
                                        hover:file:bg-violet-100
                                    ">
                                    @error('materi_file') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                    <div wire:loading wire:target="materi_file">Mengunggah...</div>
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" wire:click="closeMateriModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>