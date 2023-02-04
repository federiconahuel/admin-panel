<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Str;
use \Cviebrock\EloquentSluggable\Services\SlugService;



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
    }

    public function save (Request $request, $id) {
        $publish = $request->boolean('publish');
        $article = Article::find($id);
        if($article != null){
            if($article->title != $request->title) {
                $article->title = $request->title;
                $article->slug = SlugService::createSlug(Article::class, 'slug', $request->title);
            }
            if($article->draft_content != $request->content) {
                $article->draft_content = $request->content;
                $article->draft_last_update = now(); 
            }
            $article->save();
        } else {
            Article::create([
                'id' => $id,
                'title' => $request->title,
                'draft_content' => $request->content,
                'draft_last_update' => now()
            ]);
        }
        if($publish){
            Article::where('id', $id)
                ->update([
                    'is_published' => true,
                    'publication_last_update' => now(),     
                    'publication_content' =>  $request->content
                ]);
        }    
        return response()->noContent();
    }

    public function unpublish (Request $request, $id) {
        $article = Article::find($id);
        $article->is_published = false;
        $article->save();
        return redirect()->route('edit', [
            'id' => $id
        ]); 
    }

    public function loadSearchArticlesView(){
        $articles = Article::paginate(5);
        return view('search-articles', compact('articles'));
    }

}
