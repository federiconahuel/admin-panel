<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Article extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Sluggable;

    protected $fillable = [
        'id',
        'title',
        'draft_content',
        'draft_last_update',
        'is_published',
        'publication_content',
        'publication_last_update'
    ];

    public $incrementing = false;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function createdAt(): Attribute
    {
        return new Attribute(
            get: function( $originalValue ){
                return Carbon::createFromTimestamp(strtotime($originalValue))
                ->timezone('America/Argentina/Buenos_Aires')
                ->format("d-m-Y H:i:s");
        });
    }

    public function draftLastUpdate(): Attribute
    {
        return new Attribute(
            get: function( $originalValue ){
                return Carbon::createFromTimestamp(strtotime($originalValue))
                ->timezone('America/Argentina/Buenos_Aires')
                ->format("d-m-Y H:i:s");
        });
    }

    public function publicationLastUpdate(): Attribute
    {
        return new Attribute(
            get: function( $originalValue ){
                return Carbon::createFromTimestamp(strtotime($originalValue))
                ->timezone('America/Argentina/Buenos_Aires')
                ->format("d-m-Y H:i:s");
        });
    }
   
}
