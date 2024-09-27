<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Choice;
use App\Models\Favorite;
use PhpParser\Node\Stmt\Foreach_;

class QuizController extends Controller
{







  public function create()
  {
    $screen_id = "create";
    return view('create_show_edit', compact("screen_id"));
  }


  // ↓ajax
  public function ajaxQuizUpdate($quiz_id)
  {
    
    if(Favorite::where('quiz_id', $quiz_id)->where('user_id', auth()->user()->id)->first()==null){
//初めてぼたんが推されたとき
      
      $favorite = new Favorite();
      $favorite->quiz_id = $quiz_id;
      $favorite->user_id= auth()->user()->id;
      $favorite->favorite_flag = 1;
      $favorite->save();
    }else{
      //2回目以降ボタンを押されたとき
      $favorite=Favorite::where('quiz_id', $quiz_id)->where('user_id', auth()->user()->id)->first();
      if($favorite->favorite_flag==1){
          $favorite->favorite_flag=0;
        }elseif($favorite->favorite_flag==0){
          $favorite->favorite_flag=1;
        }
        $favorite->update();
    }
    return $favorite->favorite_flag;
  }









  public function mobileQuizIndex()
  {
    $quizzes = Quiz::where("kind",1)->orderBy("created_at","desc")->paginate(5);
    $screen_id = "mobile_quiz";
    // $quizzes = Quiz::all();
    // $quizzes=Quiz::where("quizkind",0)->get();
    return view('mobile_quiz.index', compact("quizzes","screen_id"));
  }










  public function netQuizIndex()
  {
    $quizzes = Quiz::where("kind",0)->orderBy("created_at","desc")->paginate(5);
    $screen_id = "net_quiz";
    return view('mobile_quiz.index', compact("quizzes","screen_id"));
  }















  public function store(Request $request)
  {

    $request->session()->regenerate();
    $quiz = new Quiz();
    $quiz->quiz = $request->quiz;

    $quiz->kind = $request->quiz_kind;
    $quiz->save();
    $choice_numbers = [$request->choice1, $request->choice2, $request->choice3, $request->choice4];


    for ($i = 0; $i < 4; $i++) {
      $choice = new Choice();
      $choice->choice = $choice_numbers[$i];
      $choice->quiz_id = $quiz->id;
      if ($request->answer == $i) {
        $choice->answer = 1;
      } else {
        $choice->answer = 0;
      }
      $choice->save();
    }
    // $quizzes = Quiz::all();
    // $choices = Choice::all();
    if( $quiz->kind==0){
      return redirect()->route("netQuizIndex")->with("message","新規データを作成しました。");
    }
    elseif($quiz->kind==1){
      return redirect()->route("mobileQuizIndex")->with("message","新規データを作成しました。");
    }
  }













  public function mobileQuizShow(Quiz $quiz)
  {
    $screen_id = "show";
    $favorite_flag=0;
    if(Favorite::where('quiz_id', $quiz->id)->where('user_id', auth()->user()->id)->first()!=null){
      $favorite=Favorite::where('quiz_id', $quiz->id)->where('user_id', auth()->user()->id)->first();
      $favorite_flag=$favorite->favorite_flag;
    }
      
    return view('create_show_edit', compact("quiz", "screen_id","favorite_flag"));
  }

  








  public function mobileQuizEdit(Quiz $quiz)
  {
    $screen_id = "edit";
    return view('create_show_edit', compact("quiz", "screen_id"));
  }









  public function updateQuiz(Request $request, Quiz $quiz)
  {
    // $request->session()->regenerate();
    // ↓updateなのでnew はつかわない
    // $quiz = new Quiz(); //これは不必要:newはあたらしくデータをつくったり呼び起こす際に利用する。
    // ↑★すでにupdateQuizメソッドの第二引数としてQuiz $quizはひきうけている。
    $quiz->quiz = $request->quiz;
    $quiz->kind = $request->quiz_kind;
    $quiz->update();



    $choice_numbers = [$request->choice1, $request->choice2, $request->choice3, $request->choice4];

    // いったんこれでいま、引き受けている変数の中身を確認しよう。
    // foreachの基本
    // $fruits = ["apple", "banana", "orange"];
    // $fruits[]="grape";
    // $fluit_text="";
    // foreach($fruits as $key => $fruit){
    //     $fluit_text=$fluit_text.$fruit."を".$key."個";
    //     if($key==3){
    //         $fluit_text=$fluit_text."買う";
    //     }
    // }




    foreach ($quiz->choices as $key => $choice) {
      //★$choice->choice:選択肢の文字列 今回でいうと$choice_numbersの中に格納されているそれぞれの文字列。[配列の取り出し方]で検索
      // ★$choice->answer:追記する必要あり 例↓
      if ($request->answer == $key) {
        $choice->answer = 1;
      } else {
        $choice->answer = 0;
      }
      // ★$choice->quiz_id:OK
      $choice->choice = $choice_numbers[$key];
      $choice->quiz_id = $quiz->id;

      // ↓★DBのデータの新規作成のときはsave()メソッドをつかうが更新のときにはupdate()メソッドをつかう。
      $choice->update();
      // $choice->update();

    }
    // ↓一覧画面にするときには全部のデータをとってくるので良い※$choicesは、$quizのリレーションでひっぱてくれるので不要
    // $quizzes = Quiz::all();
    // $choices = Choice::all();
    // return view('mobile_quiz.index', compact("quizzes", "choices"));
    // ↓★ここは、更新後にどこの画面に遷移したいかで書き方がかわるが、いったんデータが更新したかどうか確認したいなら、show画面でよさそう。
    return redirect()->route("mobileQuizShow", [$quiz])
      ->with("message", "データを更新しました。");
  }

  // 正誤判定↓
  public function checkAnswer(Request $request, Quiz $quiz)
  {
    $choice = Choice::where("id", $request->answer)->first();
    if (!$choice) {
      // もし $choice が null であれば、エラーメッセージを返す
      $screen_id = "show";
      $correct_or_error = null;
      $select_choice = null;
      return view('create_show_edit', compact("quiz", "screen_id", "correct_or_error", "select_choice"));
    }

    if ($choice->answer == 1) {

      $screen_id = "show";
      $correct_or_error = 1;
      $select_choice = $choice;
      return view('create_show_edit', compact("quiz", "screen_id", "correct_or_error", "select_choice"));
    } else {
      $screen_id = "show";
      $correct_or_error = 0;
      $select_choice = $choice;
      return view('create_show_edit', compact("quiz", "screen_id", "correct_or_error", "select_choice"));
    }

  }
}