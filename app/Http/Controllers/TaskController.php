<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\Pet;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // FROM tasksからstatusカラムがfalse(0)のタスクを持ってくる
        $tasks = Task::where('status', false)->get();

        // 前回の与えたエサを持ってくる
        $previous_feed = Task::where('status', true)->orderByDesc('updated_at')->limit(1)->get();

        // id=1のペットのHPを持ってくる
        $pet = Pet::find(1);
        $pet_hp = $pet->hp;
        
        return view('tasks.index', compact('tasks', 'pet_hp', 'previous_feed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーションルール
        $rules = [
            'task_name' => 'required|max:100',
            'task_point' => 'required|integer|between:1, 100',
        ];

        // 表示メッセージ
        $message = [
            'required' => '必須項目です', 
            "max" => '100文字以内にしてください',
            'between' => '1～100のポイントを入力してください'
        ];
        
        // 1,バリデーション対象　2,rule 3,message->validate()
        Validator::make($request->all(), $rules, $message)->validate();

        // taskを新規登録するときはnewする必要がある
        $task = new Task;

        // requestから渡ってきたinputネームタグをインスタンスの対応するカラムに代入
        $task->name = $request->input('task_name');
        $task->point = $request->input('task_point');
        
        // DBにセーブ
        $task->save();

        // リダイレクト
        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::find($id);

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 編集ボタン
        if($request->status === null){
            // バリデーションルール
            $rules = [
                'task_name' => 'required|max:100',
                'task_point' => 'required|integer|between:1, 100',
            ];

            // 表示メッセージ
            $message = [
                'required' => '必須項目です', 
                "max" => '100文字以内にしてください',
                'between' => '1～100のポイントを入力してください'
            ];
            
            Validator::make($request->all(), $rules, $message)->validate();

            $task = Task::find($id);

            $task->name = $request->input('task_name');
            $task->point = $request->input('task_point');
            
            $task->save();

        }else{
            // 完了ボタンを押したとき
            $task = Task::find($id);
            $task->status = 1;

            // 完了タスクのポイントをペットのHPに加算
            $pet = Pet::find(1);
            $pet->hp += $task->point;

            // HPの上限はとりあえず100
            if($pet->hp >= 100) {
                $pet->hp = 100;
            }

            $task->save();
            $pet->save();
        }
        // リダイレクト
        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Task::find($id)->delete();

        return redirect('/tasks');
    }
}
