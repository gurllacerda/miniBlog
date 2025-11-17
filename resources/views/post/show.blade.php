<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-4">
                        <a href="{{ url()->previous() }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">← Voltar</a>
                    </div>

                    <article class="prose dark:prose-invert">
                        <header class="mb-4 flex items-start justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-bold">{{ $post->title }}</h1>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    por {{ $post->user->name ?? 'Autor desconhecido' }} · {{ $post->created_at ? $post->created_at->format('d/m/Y') : '' }}
                                </div>
                            </div>

                            @if(!empty($img) && isset($img['body'], $img['content_type']))
                                <img
                                    src="data:{{ $img['content_type'] }};base64,{{ base64_encode($img['body']) }}"
                                    alt="imagem customizada"
                                    style="width:150px;height:60px;object-fit:cover"
                                    class="rounded-md shadow-sm"
                                >
                            @endif
                        </header>

                        <div class="mt-4">
                            {!! nl2br(e($post->body)) !!}
                        </div>

                        <footer class="mt-6 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('posts') }}" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded text-sm">Todos os posts</a>

                                <!-- botão para abrir formulário de comentário -->
                                <button id="toggle-comment-form" class="px-2 py-1 bg-transparent text-gray-600 dark:text-gray-300 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Comentar</button>
                            </div>

                            <div class="flex items-center gap-2">
                                @can('update', $post)
                                    <a href="{{ route('posts.edit', $post) }}" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Editar</a>
                                @endcan

                                {{-- @can('delete', $post) --}}
                                     <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Confirma exclusão deste post?')">
                                         @csrf
                                         @method('DELETE')
                                        <button class="px-3 py-1 bg-transparent text-gray-700 dark:text-gray-300 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Excluir</button>
                                     </form>
                                {{-- @endcan --}}
                            </div>
                        </footer>

                    </article>

                    <!-- area de comentários -->
                     <section class="mt-8">
                        <h3 class="text-lg font-semibold mb-3">Comentários ({{ $comments->count() }})</h3>

                        <div id="comments-list" class="space-y-4">
                            @foreach($comments as $comment)
                                <div class="comment-item relative border rounded p-3 bg-gray-50 dark:bg-gray-700" data-comment-id="{{ $comment->id }}">
                                    <!-- ícones (editar = link, excluir = form) no canto superior direito -->
                                    <div class="absolute top-2 right-2 flex items-center gap-1 z-10">
                                        {{-- link para edição (recarrega a página) --}}
                                        <button
                                            type="button"
                                            class="edit-comment p-1 text-gray-600 dark:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700"
                                            data-comment-id="{{ $comment->id }}"
                                            data-update-url="{{ route('comments.update', [$post, $comment]) }}"
                                            title="Editar"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M4 13.5V20h6.5L20.873 9.627a1 1 0 000-1.414L16.873 4.414a1 1 0 00-1.414 0L4 15.873z" />
                                            </svg>
                                        </button>

                                        {{-- formulário de exclusão (recarrega a página) --}}
                                        <form action="{{ route('comments.destroy', [$post, $comment]) }}" method="POST" onsubmit="return confirm('Confirma exclusão deste comentário?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-600 dark:text-gray-300 rounded hover:bg-gray-100 dark:hover:bg-gray-700" title="Excluir">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-7 4h10" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="flex justify-between items-start">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">
                                            <strong>{{ $comment->user->name ?? 'Usuário' }}</strong> · <span class="text-xs">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    <div class="comment-body mt-2 text-gray-800 dark:text-gray-100">{{ $comment->body }}</div>

                                    {{-- sem edição AJAX inline: edição/exclusão recarregam a página --}}
                                </div>
                            @endforeach
                        </div>

                        <!-- formulário escondido por padrão -->
                        <div id="comment-form-wrapper" class="mt-4 hidden">
                            <form id="comment-form" class="space-y-2">
                                @csrf
                                <textarea id="comment-body" name="body" rows="3" class="w-full rounded border-gray-300 dark:border-gray-600 p-2" placeholder="Escreva seu comentário..."></textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="px-3 py-1 bg-transparent text-gray-700 dark:text-gray-100 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Comentar</button>
                                    <button id="cancel-comment" type="button" class="px-3 py-1 bg-transparent text-gray-600 dark:text-gray-300 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Cancelar</button>
                                </div>
                            </form>
                        </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        (function(){
            const toggleBtn = document.getElementById('toggle-comment-form');
            const formWrapper = document.getElementById('comment-form-wrapper');
            const cancelBtn = document.getElementById('cancel-comment');
            const commentForm = document.getElementById('comment-form');
            const commentBody = document.getElementById('comment-body');
            const commentsList = document.getElementById('comments-list');

            toggleBtn && toggleBtn.addEventListener('click', function(){
                formWrapper.classList.toggle('hidden');
                if (commentBody) commentBody.focus();
            });

            cancelBtn && cancelBtn.addEventListener('click', function(){
                formWrapper.classList.add('hidden');
                if (commentBody) commentBody.value = '';
            });

            commentForm && commentForm.addEventListener('submit', function(e){
                e.preventDefault();
                const body = commentBody.value.trim();
                if(!body) return alert('Comentário vazio.');

                fetch("{{ route('comments.store', $post) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ body })
                })
                .then(r => {
                    if (!r.ok) throw new Error('Network response was not ok');
                    return r.json();
                })
                .then(data => {
                    // montar novo comentário e inserir no topo
                    const wrapper = document.createElement('div');
                    wrapper.className = 'comment-item relative border rounded p-3 bg-gray-50 dark:bg-gray-700';
                    if (data.id) wrapper.setAttribute('data-comment-id', data.id);

                    const meta = document.createElement('div');
                    meta.className = 'text-sm text-gray-600 dark:text-gray-300';
                    meta.innerHTML = `<strong>${escapeHtml(data.user_name ?? 'Você')}</strong> · <span class="text-xs">${escapeHtml(data.created_at ?? '')}</span>`;

                    const content = document.createElement('div');
                    content.className = 'comment-body mt-2 text-gray-800 dark:text-gray-100';
                    content.textContent = data.body ?? body;

                    wrapper.appendChild(meta);
                    wrapper.appendChild(content);

                    // inserir no topo
                    commentsList.insertBefore(wrapper, commentsList.firstChild);

                    // limpar e esconder formulário
                    commentBody.value = '';
                    formWrapper.classList.add('hidden');
                })
                .catch(err => {
                    console.error(err);
                    alert('Erro ao enviar comentário.');
                });
            });

            // edição inline de comentário (delegation)
            commentsList && commentsList.addEventListener('click', function(e){
                const btn = e.target.closest('.edit-comment');
                if(!btn) return;

                const commentId = btn.dataset.commentId;
                const updateUrl = btn.dataset.updateUrl;
                const item = commentsList.querySelector(`[data-comment-id="${commentId}"]`);
                if(!item) return;

                const bodyEl = item.querySelector('.comment-body');
                if(!bodyEl) return;

                // evita abrir múltiplos formulários no mesmo comentário
                if (item.querySelector('form.inline-edit')) return;

                const originalText = bodyEl.textContent.trim();

                // criar formulário inline
                const form = document.createElement('form');
                form.className = 'inline-edit mt-2';
                form.innerHTML = `
                    <textarea name="body" rows="3" class="w-full rounded border-gray-300 dark:border-gray-600 p-2">${escapeHtml(originalText)}</textarea>
                    <div class="flex gap-2 mt-2">
                        <button type="submit" class="px-3 py-1 bg-transparent text-gray-700 dark:text-gray-100 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Salvar</button>
                        <button type="button" class="cancel px-3 py-1 bg-transparent text-gray-600 dark:text-gray-300 rounded text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Cancelar</button>
                    </div>
                `;

                bodyEl.style.display = 'none';
                bodyEl.after(form);
                const textarea = form.querySelector('textarea');

                // foco no textarea
                textarea.focus();

                form.addEventListener('submit', function(ev){
                    ev.preventDefault();
                    const newBody = textarea.value.trim();
                    if(!newBody) return alert('Comentário vazio.');

                    fetch(updateUrl, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ body: newBody })
                    })
                    .then(r => {
                        if (!r.ok) return r.json().then(j => { throw j; }).catch(() => { throw new Error('Network response not ok'); });
                        return r.json().catch(() => null);
                    })
                    .then(data => {
                        // atualizar DOM com resposta do servidor ou texto enviado
                        bodyEl.textContent = data && data.body ? data.body : newBody;
                        bodyEl.style.display = 'block';
                        form.remove();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Erro ao atualizar comentário.');
                    });
                });

                form.querySelector('.cancel').addEventListener('click', function(){
                    form.remove();
                    bodyEl.style.display = 'block';
                });
            });


            function escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }
        })();
    </script>
</x-app-layout>