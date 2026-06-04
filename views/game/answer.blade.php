<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>答え | 歌詞当てゲーム</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex items-center justify-center">

    <div class="max-w-md w-full mx-4 bg-white p-8 rounded-2xl shadow-xl border border-slate-100 text-center">
        
        @if($mode === 'input')
            @if($isCorrect)
                <div class="mb-4 inline-block px-6 py-2 bg-emerald-100 text-emerald-700 font-black rounded-full text-xl shadow-sm">
                    🎉 正解！（50%以上一致）
                </div>
            @else
                <div class="mb-4 inline-block px-6 py-2 bg-rose-100 text-rose-700 font-black rounded-full text-xl shadow-sm">
                    ❌ 不正解…
                </div>
            @endif

            <div class="my-6 p-4 bg-slate-50 rounded-xl border border-slate-100 shadow-inner">
                <p class="text-xs font-bold text-slate-400 mb-1 tracking-wider uppercase">Match Rate</p>
                <div class="text-3xl font-black {{ $isCorrect ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ round($percent, 1) }} %
                </div>
                <div class="w-full bg-slate-200 h-2 rounded-full mt-3 overflow-hidden">
                    <div class="h-full {{ $isCorrect ? 'bg-emerald-500' : 'bg-rose-500' }} transition-all duration-500" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        @else
            <p class="text-xs font-bold text-indigo-500 tracking-widest uppercase mb-2">Opening Lyrics</p>
            <h2 class="text-sm font-medium text-slate-400 mb-6">正解の歌詞は…</h2>
        @endif

        @if($song->cover_image_url)
            <div class="mb-6 flex justify-center">
                <img src="{{ $song->cover_image_url }}" alt="ジャケット写真" class="w-40 h-40 rounded-xl shadow-md border border-slate-100 object-cover">
            </div>
        @endif

        <div class="space-y-3 text-left mb-6">
            @if($mode === 'input')
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 mb-1">あなたの回答：</p>
                    <p class="text-sm text-slate-700 font-bold">「{{ $userInput ?: '（未入力）' }}」</p>
                </div>
            @endif
            
            <div class="bg-indigo-50/50 p-3 rounded-xl border border-indigo-100">
                <p class="text-xs font-semibold text-indigo-400 mb-1">正解の歌詞：</p>
                <p class="text-sm text-indigo-900 font-black">「{{ $song->opening_lyrics }}」</p>
            </div>
        </div>

        <div class="border-t border-slate-100 pt-4 mb-6">
            <p class="text-xs font-semibold text-slate-400 mb-1">曲名：</p>
            <p class="text-sm text-slate-800 font-extrabold">{{ $song->title }}</p>
        </div>

        <div class="space-y-3">
            <a href="{{ route('game.question') }}" 
                class="block w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl text-center shadow-lg shadow-emerald-100 transition duration-200 cursor-pointer">
                ⏭️ 次の問題に挑戦する
            </a>

            <a href="{{ route('game.reset') }}" 
                class="block w-full py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-center transition duration-200 cursor-pointer">
                🏠 難易度選択に戻る
            </a>
        </div>

    </div>

</body>
</html>