<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    public function insertBlog(Request $request)
{
    try {
        $validatedData = $request->validate([
            'blog_title' => 'required|string|max:255',
            'blog_sub_title' => 'required|string|max:255',
            'blog_description' => 'required|string',
            'author_name' => 'required|string',
        ]);

        $blogFilePath = '';
        $blogFileType = '';
        $blogFileName = '';
        $authorFilePath = '';
        $authorFileType = '';
        $authorFileName = '';

        if ($request->hasFile('blog_img')) {
            $blogFile = $request->file('blog_img');
            $blogFileName = time() . '_' . $blogFile->getClientOriginalName();
            $blogFilePath = $blogFile->storeAs('blog_img', $blogFileName, 'public');
            $blogFileType = $blogFile->getMimeType();
        }

        if ($request->hasFile('author_img')) {
            $authorFile = $request->file('author_img');
            $authorFileName = time() . '_' . $authorFile->getClientOriginalName();
            $authorFilePath = $authorFile->storeAs('author_img', $authorFileName, 'public');
            $authorFileType = $authorFile->getMimeType();
        }

        // Create Blog
        $blog = new Blog();
        $blog->blog_title = $validatedData['blog_title'];
        $blog->blog_sub_title = $validatedData['blog_sub_title'];
        $blog->blog_description = $validatedData['blog_description'];
        $blog->blog_img = $blogFilePath;
        $blog->blog_img_type = $blogFileType;
        $blog->blog_img_name = $blogFileName;
        $blog->author_name = $validatedData['author_name'];
        $blog->author_img = $authorFilePath;
        $blog->author_img_type = $authorFileType;
        $blog->author_img_name = $authorFileName;
        $blog->created_by = Auth::id();
        $blog->active_yn = $request->input('active_yn');
        $blog->save();

        return response()->json([
            'message' => 'Blog created successfully!',
            'blog' => $blog,
        ], 200);

    } catch (ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed.',
            'details' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'An error occurred during blog creation.',
            'details' => $e->getMessage(),
        ], 500);
    }
}


    public function getActiveBlogs()
    {
        $blogs = Blog::where('active_yn', 'Y')->get();

        if ($blogs) {
            return response()->json([
                'blogs' => $blogs
            ], 200);
        } else {
            return response()->json([
                'message' => 'Blogs not found'
            ], 404);
        }
    }

    public function getActiveBlog($blogId)
    {
        $blog = Blog::find($blogId);

        if ($blog) {
            return response()->json([
                'blog' => $blog
            ], 200);
        } else {
            return response()->json([
                'message' => 'Blogs not found'
            ], 404);
        }
    }

    public function updateBlog(Request $request, $id)
    {
        try {
            // Validate input data
            $validatedData = $request->validate([
                'blog_title' => 'required|string|max:255',
                'blog_sub_title' => 'required|string|max:255',
                'blog_description' => 'required|string',
                'author_name' => 'required|string',
            ]);

            $blog = Blog::find($id);

            if (!$blog) {
                return response()->json([
                    'error' => 'Blog not found.',
                ], 404);
            }

            if ($request->hasFile('blog_img')) {
                $blogFile = $request->file('blog_img');

                // Get file details
                $blogFileName = time() . '_' . $blogFile->getClientOriginalName();
                $blogFilePath = $blogFile->storeAs('blog_img', $blogFileName, 'public');  // Save file to public storage
                $blogFileType = $blogFile->getMimeType();  // Get the file MIME type

                $service->blog_img = $blogFilePath;
                $service->blog_img_type = $blogFileType;
                $service->blog_img_name = $blogFileName;
            }

            if ($request->hasFile('author_img')) {
                $authorFile = $request->file('author_img');

                // Get file details
                $authorFileName = time() . '_' . $authorFile->getClientOriginalName();
                $authorFilePath = $authorFile->storeAs('author_img', $authorFileName, 'public');
                $authorFileType = $authorFile->getMimeType();

                $service->author_img = $authorFilePath;
                $service->author_img_type = $authorFileType;
                $service->author_img_name = $author_img_name;
            }

            $blog->blog_title = $validatedData['blog_title'];
            $blog->blog_sub_title = $validatedData['blog_sub_title'];
            $blog->blog_description = $validatedData['blog_description'] ?? '';;
            $blog->blog_img = $blogFilePath ?? '';
            $blog->blog_img_type = $blogFileType ?? '';
            $blog->blog_img_name = $blogFileName ?? '';
            $blog->author_name = $validatedData['author_name'];
            $blog->author_img = $authorFilePath ?? '';
            $blog->author_img_type = $authorFileType ?? '';
            $blog->author_img_name = $author_img_name ?? '';
            $blog->updated_by = Auth::user()->id;
            $blog->active_yn = $request->input('active_yn');
            $blog->save();

            return response()->json([
                'message' => 'Blog updated successfully!',
                'blog' => $blog,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during blog update.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


}
