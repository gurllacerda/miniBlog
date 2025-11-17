<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Criar Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="/posts" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                value="{{ old('title') }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            >
                            @error('title')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo</label>
                            <textarea
                                id="body"
                                name="body"
                                rows="6"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            >{{ old('body') }}</textarea>
                            @error('body')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="/posts" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">Voltar</a>

                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    id="generate-idea"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold text-sm rounded-md shadow-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-300 transition"
                                >
                                    Gerar ideia
                                </button>

                                <button
                                    type="submit"
                                    class="inline-flex items-center px-5 py-2 bg-yellow-400 text-black font-semibold text-sm rounded-md shadow-lg
                                           hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-300
                                           transform hover:-translate-y-0.5 transition"
                                >
                                    Publicar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const btn = document.getElementById('generate-idea');
            const title = document.getElementById('title');
            const body = document.getElementById('body');

            btn && btn.addEventListener('click', function(){
                btn.disabled = true;
                const originalText = btn.textContent;
                btn.textContent = 'Gerando...';

                fetch("{{ route('posts.generate') }}", { method: 'GET', headers: { 'Accept': 'application/json' } })
                    .then(res => {
                        if (!res.ok) return res.json().then(j => { throw j; }).catch(() => { throw new Error('Network response not ok'); });
                        return res.json().catch(() => null);
                    })
                    .then(data => {
                        if (data) {
                            if (data.title) title.value = data.title;
                            if (data.description) body.value = data.description;
                        }
                    })
                    .catch(err => {
                        alert('Erro ao gerar ideia. Veja console/network e logs.');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.textContent = originalText;
                    });
            });
        })();
    </script>
</x-app-layout>
