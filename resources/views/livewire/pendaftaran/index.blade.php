<!-- FILE: C:\uas_kursus\laravel_app\resources\views\livewire\pendaftaran\index.blade.php -->

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Manajemen Pendaftaran Kursus
                </h2>

                <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    Tambah Pendaftaran
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
                            <th class="py-3 px-6 text-left">Kursus</th>
                            <th class="py-3 px-6 text-left">Peserta</th>
                            <th class="py-3 px-6 text-left">Status</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 dark:text-gray-400 text-sm font-light">
                        @foreach($registrations as $reg)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $reg->kursus->nama_kursus ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ $reg->peserta->name ?? 'N/A' }}</td>
                                <td class="py-3 px-6 text-left">{{ ucfirst($reg->status) }}</td>
                                <td class="py-3 px-6 text-center">
                                    <button wire:click="edit({{ $reg->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs">Edit</button>
                                    <button wire:click="delete({{ $reg->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs ml-2">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Modal Tambah/Edit Pendaftaran --}}
                @if($isOpen)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ $registration_id ? 'Edit Pendaftaran' : 'Tambah Pendaftaran' }}
                            </h3>
                            <form wire:submit.prevent="store">
                                <div class="mb-4">
                                    <label for="kursus_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Kursus:</label>
                                    <select id="kursus_id" wire:model="kursus_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Pilih Kursus</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->nama_kursus }}</option>
                                        @endforeach
                                    </select>
                                    @error('kursus_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="peserta_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Peserta:</label>
                                    <select id="peserta_id" wire:model="peserta_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="">Pilih Peserta</option>
                                        @foreach($pesertas as $peserta)
                                            <option value="{{ $peserta->id }}">{{ $peserta->name }} ({{ $peserta->email }})</option>
                                        @endforeach
                                    </select>
                                    @error('peserta_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="status" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Status:</label>
                                    <select id="status" wire:model="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="pending">Pending</option>
                                        <option value="terdaftar">Terdaftar</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex items-center justify-end mt-4">
                                    <button type="button" wire:click="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Batal</button>
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>