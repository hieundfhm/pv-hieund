<?php

namespace App\Http\Controllers;

use App\Models\Blogs;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    public function store(Request $request)
    {
        $params  = $request->all();
        $newBlog = new Blogs();
        $newBlog->fill($params);
        $newBlog->save();

        return $newBlog;
    }

    public function show($id)
    {
        return Blogs::query()->where('id', $id)->first();
    }

    public function update($id, Request $request)
    {
        $params = $request->all();

        return Blogs::query()->where('id', $id)->update([
                                                            'title'       => $params['title'],
                                                            'description' => $params['description'],
                                                            'content'     => $params['content'],
                                                            'status'      => $params['status'] ?? true,
                                                        ]);;
    }

    public function destroy($id)
    {
        return Blogs::query()->where('id', $id)->delete();
    }
}
