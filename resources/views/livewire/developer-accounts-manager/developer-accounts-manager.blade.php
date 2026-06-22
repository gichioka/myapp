<div class="min-h-screen bg-slate-950 text-slate-100">

    <div class="max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black tracking-tight text-white">
                        Developer Accounts
                    </h1>
                    <p class="text-slate-400 mt-2">
                        GitHub・Docker・Redmine・SVN アカウント統合管理
                    </p>
                </div>
            </div>
        </div>

        {{-- Flash Message --}}
        @if ($message)
            <div class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-emerald-400 font-medium">
                            {{ $message }}
                        </span>
                    </div>
                    <button wire:click="$set('message','')" class="text-emerald-400 hover:text-white">
                        ✕
                    </button>
                </div>
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <div class="text-slate-500 text-xs uppercase">Total</div>
                <div class="text-3xl font-bold mt-2">{{ $accountsList->count() }}</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <div class="text-slate-500 text-xs uppercase">GitHub</div>
                <div class="text-3xl font-bold mt-2 text-indigo-400">{{ $accountsList->where('tool_type','github')->count() }}</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <div class="text-slate-500 text-xs uppercase">Docker</div>
                <div class="text-3xl font-bold mt-2 text-sky-400">{{ $accountsList->where('tool_type','docker')->count() }}</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <div class="text-slate-500 text-xs uppercase">Redmine</div>
                <div class="text-3xl font-bold mt-2 text-rose-400">{{ $accountsList->where('tool_type','redmine')->count() }}</div>
            </div>
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <div class="text-slate-500 text-xs uppercase">SVN</div>
                <div class="text-3xl font-bold mt-2 text-orange-400">{{ $accountsList->where('tool_type','svn')->count() }}</div>
            </div>
        </div>

        {{-- Search --}}
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 mb-8">
            <div class="flex flex-col lg:flex-row gap-4">
                <select wire:model.live="filterType" class="lg:w-56 rounded-xl bg-slate-950 border border-slate-700 p-3 text-slate-100 focus:outline-none focus:border-indigo-500">
                    <option value="">すべて</option>
                    <option value="github">GitHub</option>
                    <option value="docker">Docker</option>
                    <option value="redmine">Redmine</option>
                    <option value="svn">SVN</option>
                </select>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="ラベル・ユーザー名・URL検索" class="flex-1 rounded-xl bg-slate-950 border border-slate-700 p-3 text-slate-100 focus:outline-none focus:border-indigo-500">
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

            {{-- Left Form --}}
            <div class="xl:col-span-4">
                <div class="sticky top-6 bg-slate-900 border border-slate-800 rounded-2xl p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold text-white">
                            {{ $editingId ? 'Edit Account' : 'Create Account' }}
                        </h2>
                        <p class="text-slate-500 text-sm mt-1">
                            開発アカウント情報を管理します
                        </p>
                    </div>

                    <form wire:submit.prevent="{{ $editingId ? 'update' : 'create' }}" class="space-y-5">
                        {{-- ★ User 選択セレクトボックス --}}
                        <div>
                            <label class="block text-sm text-slate-400 mb-2">User (アカウント所有者)</label>
                            <select wire:model="user_id" class="w-full rounded-xl bg-slate-950 border border-slate-700 p-3 text-slate-100 focus:outline-none focus:border-indigo-500">
                                <option value="">ユーザーを選択してください</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-slate-400 mb-2">Tool Type</label>
                            <select wire:model="tool_type" class="w-full rounded-xl bg-slate-950 border border-slate-700 p-3 text-slate-100 focus:outline-none focus:border-indigo-500">
                                <option value="github">GitHub</option>
                                <option value="docker">Docker</option>
                                <option value="redmine">Redmine</option>
                                <option value="svn">SVN</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm text-slate-400 mb-2">Label</label>
                            <input type="text" wire:model="label" class="w-full rounded-xl bg-slate-950 border border-slate-700 p-3 text-slate-100 focus:outline-none focus:border-indigo-500">
                            @error('label')
                                <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-slate-400 mb-2">Username</label>
                            <input type="text" wire:model="username" class="w-full rounded-xl bg-slate-950 border border-slate-700 p-3 text-slate-100 focus:outline-none focus:border-indigo-500">
                            @error('username')
                                <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-slate-400 mb-2">Password / Token</label>
                            <input type="password" wire:model="password" class="w-full rounded-xl bg-slate-950 border border-slate-700 p-3 text-slate-100 focus:outline-none focus:border-indigo-500">
                            @error('password')
                                <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-slate-400 mb-2">URL</label>
                            <input type="text" wire:model="url" class="w-full rounded-xl bg-slate-950 border border-slate-700 p-3 text-slate-100 focus:outline-none focus:border-indigo-500">
                            @error('url')
                                <div class="text-rose-400 text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="pt-2">
                            @if($editingId)
                                <div class="flex gap-3">
                                    <button type="button" wire:click="cancelEdit" class="flex-1 py-3 rounded-xl bg-slate-700 hover:bg-slate-600 transition">Cancel</button>
                                    <button type="submit" class="flex-1 py-3 rounded-xl bg-amber-600 hover:bg-amber-500 font-semibold transition">Update</button>
                                </div>
                            @else
                                <button type="submit" class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 font-semibold transition">Create Account</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right List --}}
            <div class="xl:col-span-8">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-800 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-white">Account List</h3>
                            <p class="text-slate-500 text-sm">登録済みアカウント</p>
                        </div>
                        <div class="px-3 py-2 rounded-xl bg-slate-800 text-sm text-slate-300">
                            {{ $accountsList->count() }} Records
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-slate-950 border-b border-slate-800">
                                    <th class="text-left p-4 text-xs uppercase text-slate-500">Tool</th>
                                    <th class="text-left p-4 text-xs uppercase text-slate-500">User</th>{{-- ★User列のヘッダー --}}
                                    <th class="text-left p-4 text-xs uppercase text-slate-500">Label</th>
                                    <th class="text-left p-4 text-xs uppercase text-slate-500">Username</th>
                                    <th class="text-left p-4 text-xs uppercase text-slate-500">URL</th>
                                    <th class="text-left p-4 text-xs uppercase text-slate-500">Security</th>
                                    <th class="text-right p-4 text-xs uppercase text-slate-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accountsList as $account)
                                    <tr class="border-b border-slate-800 hover:bg-slate-800/40 transition">
                                        <td class="p-4">
                                            @if($account->tool_type === 'github')
                                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">GitHub</span>
                                            @elseif($account->tool_type === 'docker')
                                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-sky-500/10 text-sky-400 border border-sky-500/20">Docker</span>
                                            @elseif($account->tool_type === 'redmine')
                                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">Redmine</span>
                                            @elseif($account->tool_type === 'svn')
                                                <span class="px-3 py-1 rounded-full text-xs font-medium bg-orange-500/10 text-orange-400 border border-orange-500/20">SVN</span>
                                            @endif
                                        </td>
                                        {{-- ★User名を表示。リレーションのおかげで安全にオプショナルチェイン(?->)で呼び出せます --}}
                                        <td class="p-4">
                                            <div class="text-sm font-medium text-slate-300">
                                                {{ $account->user?->name ?? '未設定' }}
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="font-semibold text-white">{{ $account->label }}</div>
                                        </td>
                                        <td class="p-4">
                                            <span class="font-mono text-slate-300">{{ $account->username ?: '-' }}</span>
                                        </td>
                                        <td class="p-4 max-w-xs">
                                            <div class="truncate text-slate-400" title="{{ $account->url }}">
                                                {{ $account->url ?: '-' }}
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                                <span class="text-xs text-emerald-400 font-medium">AES-256</span>
                                            </div>
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <button wire:click="edit({{ $account->id }})" class="px-3 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-medium transition">編集</button>
                                                <button wire:click="delete({{ $account->id }})" wire:confirm="本当に削除しますか？" class="px-3 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-medium transition">削除</button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- colspanを6から「7」に増やしています --}}
                                        <td colspan="7" class="p-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-20 h-20 text-slate-700 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 7.5l-.625 10.632A2.25 2.25 0 0117.378 20.25H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5" />
                                                </svg>
                                                <h3 class="text-xl font-bold text-white mb-2">No Accounts Found</h3>
                                                <p class="text-slate-500">左側フォームから新しいアカウントを登録してください</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer Stats --}}
                <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                        <div class="text-slate-500 text-xs uppercase">GitHub</div>
                        <div class="text-xl font-bold text-indigo-400 mt-1">{{ $accountsList->where('tool_type','github')->count() }}</div>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                        <div class="text-slate-500 text-xs uppercase">Docker</div>
                        <div class="text-xl font-bold text-sky-400 mt-1">{{ $accountsList->where('tool_type','docker')->count() }}</div>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                        <div class="text-slate-500 text-xs uppercase">Redmine</div>
                        <div class="text-xl font-bold text-rose-400 mt-1">{{ $accountsList->where('tool_type','redmine')->count() }}</div>
                    </div>
                    <div class="bg-slate-900 border border-slate-800 rounded-xl p-4">
                        <div class="text-slate-500 text-xs uppercase">SVN</div>
                        <div class="text-xl font-bold text-orange-400 mt-1">{{ $accountsList->where('tool_type','svn')->count() }}</div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>