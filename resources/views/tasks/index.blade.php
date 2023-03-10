<?php 
    $now = time();            
    $born = strtotime("2023-02-7-10:00:00");

    $alive_time = floor(($now - $born) / 60 / 60 / 24 );
    $alived_time = $alive_time;
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Todo</title>

    @vite('resources/css/app.css')
</head>

<body class="flex flex-col min-h-[100vh] bg-blue-50">
    <header class="bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="py-6">
                <p class="text-white text-xl anime_test">山田君育成アプリ : タスクをクリアして山田君をなるべく長く生かそう！</p>
            </div>
        </div>
    </header>

    <main class="grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
	    <div class="py-[30px]">

		@if($previous_feed->isNotEmpty())
		<p class="text-2xl font-bold text-center">前回の食べたもの： {{ $previous_feed[0]->name }}</p>
		@endif

        @if($pet_hp > 0)
            <div class="text-center balloon2 w-screen flex justify-center items-center">
                <p>私の戦闘力は{{ $pet_points }} です</p>
            </div>
                <div class="flex fuwafuwa">
                    <img src="{{ asset('images\school_gakuran_boy2.png') }}" alt="" width="100">
		        </div>
        <div class="flex justify-center">
            {{-- HPの色判定 --}}
            <p class="text-2xl font-bold text-center">山田 残りHP:</p> 
                @if( $pet_hp >= 70 )
                 <p class="mr-5 inline-block text-green-500 text-2xl font-bold">{{ $pet_hp }}</p>
                @endif
                @if( $pet_hp <= 69 && $pet_hp >= 30 )
                 <p class="mr-5 inline-block text-yellow-500 text-2xl font-bold">{{ $pet_hp }}</p>
                @endif
                @if( $pet_hp <= 29 )
                <p class="mr-5 inline-block text-red-500 text-2xl font-bold">{{ $pet_hp }}</p> @endif
                <p class="block text-2xl font-bold text-center">生存日数:<?= $alive_time; ?>日</p>
            </div>
            @endif
            {{-- HP0で死んでいた時の表示 --}}
            @if($pet_hp === 0 )
            <div class="fuwafuwa">
                <img src="{{ asset('images\12004.png') }}" alt="" width="100"> </div> <div class="text-center">
               <p class="inline-block text-2xl font-bold">山田は死にました。あなたのせいです。</p>
               <p class="inline-block text-2xl font-bold">山田が生きたのは<?= $alived_time ?>日でした</p>
            </div>
            </div>
        @endif
        
                <form action="/tasks" method="post" class="mt-10">
                    @csrf
                    <div class="flex flex-col items-center">
                        <label class="w-full max-w-3xl mx-auto">
                            <input
                                class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-4 pl-4 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm"
                                placeholder="タスクを入力    ex.リングフィットする..." type="text" name="task_name" />
                            @error('task_name')
                                <div class="mt-3">
                                    <p class="text-red-500">
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                            <input
                                class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-4 pl-4 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm"
                                placeholder="クリアポイントを入力 (1～100)" type="number" name="task_point" />
                            @error('task_point')
                                <div class="mt-3">
                                    <p class="text-red-500">
                                        {{ $message }}
                                    </p>
                                </div>
                            @enderror
                        </label>

                        <button type="submit"
                            class="mt-5 p-4 rounded-lg bg-slate-800 text-white w-full max-w-xs hover:bg-slate-900 transition-colors">
                            追加
                        </button>
                    </div>

                </form>

                {{-- 追記 --}}
                @if ($tasks->isNotEmpty())
                    <div class="max-w-7xl mx-auto mt-5 sm:mt-10">
                        <div class="inline-block min-w-full py-2 align-middle">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                            <th scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                                                タスク</th>
                                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach ($tasks as $item)
                                            <tr>
                                                <td class="px-3 py-4 text-sm">
                                                    <div>
                                                        <p class="text-blue-500 text-lg">{{ $item->point }}P</p> {{ $item->name }}
                                                    </div>
                                                </td>
                                                <td class="p-0 text-right text-sm font-medium">
                                                    <div class="flex justify-end">
                                                        <div>
                                                            <form action="/tasks/{{ $item->id }}" method="post"
                                                                class="inline-block text-gray-500 font-medium"
                                                                role="menuitem" tabindex="-1">
                                                                @csrf
                                                                @method('PUT')

                                                                {{-- updateメソッドは「編集する」で使われているため、判別するためのhidden --}}
                                                                <input type="hidden" name="status"
                                                                    value="{{ $item->status }}">

                                                                <button type="submit"
                                                                    class="bg-emerald-700 rounded px-2 py-4 w-12 sm:w-20 text-white md:hover:bg-emerald-800 transition-colors flex justify-center items-center">完了</button>
                                                            </form>
                                                        </div>
                                                        <div>
                                                            <a href="/tasks/{{ $item->id }}/edit/"
                                                                class="inline-block text-center py-4 w-10 sm:w-20 underline underline-offset-2 text-sky-600 md:hover:bg-sky-100 transition-colors">編集</a>
                                                        </div>
                                                        <div>
                                                            <form onsubmit="return deleteTask();"
                                                                action="/tasks/{{ $item->id }}" method="post"
                                                                class="inline-block text-gray-500 font-medium"
                                                                role="menuitem" tabindex="-1">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="py-4 w-10 sm:w-20 md:hover:bg-slate-200 transition-colors">削除</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- 追記ここまで --}}

            </div>
        </div>
    </main>
    <footer class="bg-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="py-4 text-center">
                <p class="text-white text-sm">山田育成</p>
            </div>
        </div>
    </footer>

    <script>
        function deleteTask() {
            if (confirm('本当に削除しますか？')) {
                return true;
            } else {
                return false;
            }
        }
    </script>

</body>

</html>
<!DOCTYPE html>
