<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                    Manajemen Instruktur
                </h2>

                <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    Tambah Instruktur
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
                            <th class="py-3 px-6 text-left">Nama</th>
                            <th class="py-3 px-6 text-left">Email</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 dark:text-gray-400 text-sm font-light">
                        @foreach($instructors as $instructor)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-3 px-6 text-left whitespace-nowrap">{{ $instructor->nama }}</td>
                                <td class="py-3 px-6 text-left">{{ $instructor->email }}</td>
                                <td class="py-3 px-6 text-center">
                                    <button wire:click="edit({{ $instructor->id }})" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded text-xs">Edit</button>
                                    <button wire:click="delete({{ $instructor->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs ml-2">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($isOpen)
                    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mb-4">
                                {{ $instructor_id ? 'Edit Instruktur' : 'Tambah Instruktur' }}
                            </h3>
                            <form wire:submit.prevent="store">
                                <div class="mb-4">
                                    <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nama:</label>
                                    <input type="text" id="name" wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('name') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email:</label>
                                    <input type="email" id="email" wire:model="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:bg-gray-700 dark:text-gray-200 leading-tight focus:outline-none focus:shadow-outline">
                                    @error('email') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
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