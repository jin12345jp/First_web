<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>問題 | 歌詞当てゲーム</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex items-center justify-center">

    <div class="max-w-xl w-full mx-4 bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
        
        <div class="flex justify-between items-center mb-8">
            <div class="space-x-1">
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
                    {{ session('difficulty') == 3 ? '上級' : (session('difficulty') == 2 ? '中級' : '初級') }}
                </span>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">
                    {{ session('game_mode') === 'input' ? '✍️ 入力モード' : '🧠 セルフモード' }}
                </span>
            </div>
            <span class="text-sm text-slate-400 font-medium">
                出題済み: {{ count(session('played_song_ids', [])) }} 曲
            </span>
        </div>

        <div class="text-center mb-8">
            <p class="text-xs font-bold text-indigo-500 tracking-widest uppercase mb-3">Song Title</p>
            <h1 class="text-2xl font-black text-slate-900 my-4 px-2 tracking-tight">
                {{ $song->title }}
            </h1>
            <p class="text-sm text-slate-500">
                {{ session('game_mode') === 'input' ? 'この曲の「歌い出しの歌詞」を入力してください' : 'この曲の「歌い出しの歌詞」を頭の中で思い浮かべてください' }}
            </p>
        </div>

        @if(session('game_mode') === 'input')
            <form action="{{ route('game.answer', $song->id) }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <input type="text" id="user_input" name="user_input" required autofocus autocomplete="off"
                        placeholder="ここに歌詞を入力"
                        class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white text-center font-bold text-lg transition shadow-inner">
                </div>
                <button type="submit" 
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-center shadow-lg shadow-indigo-100 transition duration-200 cursor-pointer">
                    🚀 回答を送信して判定！
                </button>
            </form>
        @else
            <div class="space-y-4">
                <a href="{{ route('game.answer', $song->id) }}" 
                    class="block w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-center shadow-lg shadow-indigo-100 transition duration-200 cursor-pointer">
                    👀 答えを見る
                </a>
            </div>
        @endif

        <div class="mt-6 text-center">
            <a href="{{ route('game.reset') }}" class="text-xs text-slate-400 hover:text-slate-600 underline transition">
                途中でやめて最初からやり直す
            </a>
        </div>

    </div>

</body>
</html>