<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Posts') }}
            </h2>

            <a href="{{ route('posts.create') }}" class="px-3 py-1 bg-indigo-600 text-white dark:bg-indigo-500 dark:text-white rounded text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600">
                Criar Post
            </a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Lista de posts com quote ao lado --}}
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Posts -->
                        <div class="flex-1">
                            @if(isset($posts) && $posts->count())
                                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach($posts as $post)
                                        <article class="p-4 bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-lg shadow-sm h-full">
                                            <header class="mb-2">
                                                <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 leading-snug">{{ $post->title }}</h3>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">por {{ $post->user->name ?? 'Autor desconhecido' }} · {{ $post->created_at ? $post->created_at->format('d/m/Y') : '' }}</div>
                                            </header>

                                            <div class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                                                {{ \Illuminate\Support\Str::limit($post->body, 120) }}
                                            </div>

                                            <footer class="mt-auto">
                                                <a href="{{ route('posts.show', $post) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Ler mais</a>
                                            </footer> 
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <p class="text-gray-600 dark:text-gray-300">Nenhum post encontrado.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Quote (sidebar) -->
                        <aside class="w-full lg:w-80">
                            <div class="bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-lg p-4 shadow-sm h-full flex flex-col">
                                <h4 class="text-sm font-semibold mb-2 text-gray-800 dark:text-gray-100">Quote do dia</h4>

                                <p class="text-sm text-gray-700 dark:text-gray-300 flex-1">
                                    {{ $quote['quote'] ?? 'Sem quote no momento.' }}
                                </p>

                                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                    — {{ $quote['author'] ?? $quote->author ?? 'Desconhecido' }}
                                </div>
                            </div>
                        </aside>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
