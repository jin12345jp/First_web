<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official髭男dism 歌詞当てゲーム</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex items-center justify-center">

    <div class="max-w-md w-full mx-4 bg-white p-8 rounded-2xl shadow-xl border border-slate-100 text-center">
        
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Official髭男dism</h1>
        <p class="text-xl font-extrabold text-indigo-600 mb-8">イントロ歌詞当てゲーム</p>

        @if (session('status'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 text-sm rounded-xl border border-emerald-200">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('game.question') }}" method="GET" class="space-y-6">
            
            <div class="text-left bg-slate-50 p-4 rounded-xl border border-slate-200/60">
                <label for="mode" class="block text-xs font-bold text-slate-400 uppercase mb-2 tracking-wider">🎲 1. ゲームモードを選択</label>
                <select id="mode" name="mode" class="w-full p-3 bg-white border border-slate-200 rounded-lg font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm cursor-pointer">
                    <option value="self">🧠 セルフジャッジ（頭の中で思い浮かべる）</option>
                    <option value="input">✍️ 歌詞入力判定（テキストで入力する）</option>
                </select>
            </div>

            <div class="text-left">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 tracking-wider明">👑 2. 難易度を選んでスタート</label>
                <div class="space-y-3 mt-2">
                    <button type="submit" name="difficulty" value="1" 
                        class="w-full py-4 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl transition duration-200 border border-indigo-200 cursor-pointer text-left px-6 flex justify-between items-center">
                        <span>🔰 初級（メインヒット曲）</span>
                        <span class="text-xs bg-indigo-200/50 px-2 py-0.5 rounded">選択</span>
                    </button>
                    
                    <button type="submit" name="difficulty" value="2" 
                        class="w-full py-4 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold rounded-xl transition duration-200 border border-amber-200 cursor-pointer text-left px-6 flex justify-between items-center">
                        <span>🎸 中級（ファンお馴染みの曲）</span>
                        <span class="text-xs bg-amber-200/50 px-2 py-0.5 rounded">選択</span>
                    </button>
                    
                    <button type="submit" name="difficulty" value="3" 
                        class="w-full py-4 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold rounded-xl transition duration-200 border border-rose-200 cursor-pointer text-left px-6 flex justify-between items-center">
                        <span>👑 上級（全楽曲からランダム）</span>
                        <span class="text-xs bg-rose-200/50 px-2 py-0.5 rounded">選択</span>
                    </button>
                </div>
            </div>
        </form>

    </div>

</body>
</html>