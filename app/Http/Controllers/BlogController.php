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

            // Convert image files to base64 if they exist
            // $blogImage = $request->hasFile('blog_img')
            //     ? 'data:' . $request->file('blog_img')->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file('blog_img')))
            //     : null;

            // $authorImage = $request->hasFile('author_img')
            //     ? 'data:' . $request->file('author_img')->getMimeType() . ';base64,' . base64_encode(file_get_contents($request->file('author_img')))
            //     : null;

            $blogImage = $this->saveBase64Image($request->blog_img, 'blog_img');
            $authorImage = $this->saveBase64Image($request->author_img, 'author_img');

            // Create Blog
            $blog = new Blog();
            $blog->blog_title = $validatedData['blog_title'];
            $blog->blog_sub_title = $validatedData['blog_sub_title'];
            $blog->blog_description = $validatedData['blog_description'];
            $blog->blog_img = $blogImage;
            $blog->author_name = $validatedData['author_name'];
            $blog->author_img = $authorImage;
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
        $blogs = Blog::all();
        if ($blogs->isEmpty()) {
            return response()->json([
                'message' => 'Blogs not found'
            ], 404);
        }

        // Convert blog images to base64 format before sending response
        $blogs = $blogs->map(function ($blog) {
            return [
                'id' => $blog->id,
                'blog_title' => $blog->blog_title,
                'blog_sub_title' => $blog->blog_sub_title,
                'blog_description' => $blog->blog_description,
                'blog_img' => $blog->blog_img ? 'data:' . $blog->blog_img_type . ';base64,' . base64_encode($blog->blog_img) : null,
                'blog_img_name' => $blog->blog_img_name,
                'blog_img_type' => $blog->blog_img_type,
                'author_name' => $blog->author_name,
                'author_img' => $blog->author_img ? 'data:' . $blog->author_img_type . ';base64,' . base64_encode($blog->author_img) : null,
                'author_img_name' => $blog->author_img_name,
                'author_img_type' => $blog->author_img_type,
                'created_by' => $blog->created_by,
                'active_yn' => $blog->active_yn,
                'created_at' => $blog->created_at,
                'updated_at' => $blog->updated_at,
            ];
        });
        
        return response()->json([
            'blogs' => $blogs
        ], 200);
    }


    public function getActiveBlog($blogId)
    {
        $blog = Blog::find($blogId);

        if (!$blog) {
            return response()->json(['error' => 'Blog not found'], 404);
        }

        return response()->json([
            'id' => $blog->id,
            'blog_title' => $blog->blog_title,
            'blog_sub_title' => $blog->blog_sub_title,
            'blog_description' => $blog->blog_description,
            'blog_img' => $blog->blog_img, // Already stored as base64
            'author_name' => $blog->author_name,
            'author_img' => $blog->author_img, // Already stored as base64
            'created_by' => $blog->created_by,
            'active_yn' => $blog->active_yn,
        ]);
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

    private function saveBase64Image($base64String)
    {
        if (!$base64String) {
            return null; // No image provided
        }

        // Ensure it's a valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
            return $base64String; // Return base64 directly
        }

        return null; // Invalid format
    }
}
