<?php

use App\Models\Noticia;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    
    $noticias = Noticia::orderBy('id', 'desc')->get();

    $noticias = Noticia::orderBy('id', 'desc')->paginate(6);


    return view('home', compact('noticias'));
})->name('home');

route::view('/teste', 'tela-teste');

route::view('/cadastro', 'tela-cadastro')->name('telaCadastro');

route::view('/login', 'login')->name('login');

route::get('/logout',
     function(Request $request){
            Auth::logout();
            $request->session()->regenerate();
            return redirect()->route('home');
     }

)->name('logout');





route::post( '/salva-usuario', 
    function(Request $request){
        $user = new User();
        $user->name =$request->nome;
        $user->email =$request->email;
        $user->password =$request->senha;
        $user->save();

        return redirect()->route('home');
    }
)
->name('SalvaUsuario');
 route::post('logar', 
    function(Request $request) 
        {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
     
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
     
                return redirect()->intended('/');
            }
     
            return back()->withErrors([
                'email' => 'email ou senha invalidos.',
            ])->onlyInput('email');
         }
)->name('logar');


Route::get('/gerencia-noticias',
function(){

    $noticias = Noticia::orderBy('id', 'desc')->get();
    return view('gerencia-noticias', compact('noticias'));

    $noticias = Noticia::orderBy('id', 'desc')->paginate(6);

    $noticias = Noticia::paginate(7);
    $noticias->items(7);
    $noticias->currentPage(7);
    $noticias->firstItem(7);
    $noticias->hasPages(17);
    
}

)->name('gerenciaNoticias')->middleware('auth');

Route::get('/cadastra-noticia',
function(){

    $noticia = new Noticia();
    return view('cadastra-noticia', compact('noticia'));
}

)->name('cadastraNoticia')->middleware('auth');



route::post( '/salva-noticia', 
    function(Request $request){
        //dd(Request $request);

        $noticia = new Noticia();
        $noticia->titulo = $request ->titulo;
        $noticia->resumo = $request ->resumo;
        $noticia->capa = $request ->capa;
        $noticia->conteudo = $request ->conteudo;

        $noticia->data = now();
        $noticia->user_id = Auth::id();
        $noticia->save();
      

        return redirect()->route('gerenciaNoticias');
    }
)
->name('SalvaNoticia')->middleware('auth');


route::get('/exibe-noticia/{noticia}',
function(Noticia $noticia){

    //$noticia = Noticia::find($noticia);

    return view('exibe-noticia', compact('noticia'));
}

)->name('exibeNoticia');


route::get('/edita-noticia/{noticia}',
function(Noticia $noticia){

    //$noticia = Noticia::find($noticia);

    

    return view('edita-noticia', compact('noticia'));
}

)->name('editaNoticia')->middleware('auth');


route::post( '/altera-noticia/{noticia}', 
    function(Request $request, Noticia $noticia) {
        //dd($request);

        $noticia = new Noticia();
        $noticia->titulo = $request ->titulo;
        $noticia->resumo = $request ->resumo;
        $noticia->capa = $request ->capa;
        $noticia->conteudo = $request ->conteudo;

        $noticia->data = now();
        $noticia->user_id = Auth::id();
        $noticia->save();
      

        return redirect()->route('gerenciaNoticias');
    }
)
->name('alteraNoticia')->middleware('auth');


route::get('/deleta-noticia/{noticia}',
function(Noticia $noticia){

    $noticia->delete();
    return redirect()->route('gerenciaNoticias');
}

)->name('deletaNoticia')->middleware('auth');


