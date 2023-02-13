<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Str;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Auth;




class ArticleController extends Controller
{
    public function create (Request $request) {
        return view('article-editor', [
            'header' =>  'Crear articulo',
            'title' => '',
            'content' => '',
            'articleID' => uniqid(),
            'slug' => '',
            'isPublished'=> false,
            'draft_last_update' => null,
            'publication_last_update' => null
        ]);
    }

    public function edit (Request $request, $id) {
        $article = Article::find($id);
        $loggedInUserID = Auth::user()->id;
        $articleUserID = $article->user->id;
        if ($article && ($loggedInUserID == $articleUserID)){
            return view('article-editor', [
                'header' =>  'Editar artículo',
                'title' => $article->title,
                'content' => $article->draft_content,
                'articleID' => $id,
                'slug' => $article->slug,
                'isPublished'=> $article->is_published,
                'draft_last_update' => $article->draft_last_update,
                'publication_last_update' => $article->publication_last_update
            ]);
        } else{
            //return 404 page
        }
    }

    public function save (Request $request, $id) {
        $article = Article::find($id);
        $publish = $request->boolean('publish');
        $loggedInUser = Auth::user();
        $date = now();
        $newTitle = $request->title == null ? 'Sin título' : $request->title;
        $newContent = $request->content == null ? '' : $request->content;
        if($article && ($article->user->id == $loggedInUser->id)){ 
            if($article->title != $newTitle) {
                $article->title = $newTitle;
                $article->slug = SlugService::createSlug(Article::class, 'slug', $newTitle);
            }
            if($article->draft_content != $newContent) {
                $article->draft_content = $newContent;
                $article->draft_last_update = $date;
            }
            $article->save();
        } else {
            $article = new Article;
            $article->id = $id;
            $article->title = $newTitle;
            $article->draft_content = $newContent;
            $article->draft_last_update = $date;
            $article->user()->associate($loggedInUser);
            $article->save();
        }
        if($publish){
            $article->is_published = true;
            $article->publication_last_update = $date;     
            $article->publication_content = $newContent;
            $article->save();
        }    
        return response()->noContent();
    }

    public function unpublish (Request $request, $id) {
        $article = Article::find($id);
        $loggedInUserID = Auth::user()->id;
        $articleUserID = $article->user->id;
        $date = now();
        if($article && ($loggedInUserID == $articleUserID)){
            $article->is_published = false;
            $article->save();
            return redirect()->route('edit-article', [
                'id' => $id
            ]); 
        } else {
            //return 404 error response
        }
    }

    public function loadSearchArticlesView(){
        $articles = Article::where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate(5);
        return view('search-articles', compact('articles'));
    }

}
