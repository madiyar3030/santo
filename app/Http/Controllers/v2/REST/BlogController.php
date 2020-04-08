<?php

namespace App\Http\Controllers\v2\REST;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request) {
        $blogs = Blog::paginate(20);
        return $blogs;
    }

    public function show($id, Request $request) {
        $blog = Blog::with(['details', 'tags', 'comments' => function($query) {
            $query->limit(10);
        }])->find($id);
        if (!$blog) return $this->Result(400, null, 'Blog not found');
        return $blog->append('author');
    }
}
