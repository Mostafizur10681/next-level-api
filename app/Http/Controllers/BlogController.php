<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    // public function insertBlog(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'blog_title' => 'required|string|max:255',
    //             'blog_sub_title' => 'required|string|max:255',
    //             'blog_description' => 'required|string',
    //             'author_name' => 'required|string',
    //         ]);

    //         $blogImage = $this->saveBase64Image($request->blog_img, 'blog_img');
    //         $authorImage = $this->saveBase64Image($request->author_img, 'author_img');

    //         // Create Blog
    //         $blog = new Blog();
    //         $blog->blog_title = $validatedData['blog_title'];
    //         $blog->blog_sub_title = $validatedData['blog_sub_title'];
    //         $blog->blog_description = $validatedData['blog_description'];
    //         $blog->blog_img = $blogImage;
    //         $blog->author_name = $validatedData['author_name'];
    //         $blog->author_img = $authorImage;
    //         $blog->created_by = Auth::id();
    //         $blog->active_yn = $request->input('active_yn');
    //         $blog->save();

    //         return response()->json([
    //             'message' => 'Blog created successfully!',
    //             'blog' => $blog,
    //         ], 200);

    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'error' => 'Validation failed.',
    //             'details' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'An error occurred during blog creation.',
    //             'details' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function insertBlog(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'blog_title' => 'required|string|max:255',
                'blog_sub_title' => 'required|string|max:255',
                'blog_description' => 'required|string',
                'author_name' => 'required|string',
                'blog_img' => 'required|string',  // Base64 string expected
                'author_img' => 'required|string' // Base64 string expected
            ]);

            $blogImgBase64 = $request->blog_img;
            $authorImgBase64 = $request->author_img;

            if (strpos($blogImgBase64, 'data:image/png;base64,') === 0) {
                $blogImgBase64 = substr($blogImgBase64, strlen('data:image/png;base64,'));
            }

            if (strpos($authorImgBase64, 'data:image/png;base64,') === 0) {
                $authorImgBase64 = substr($authorImgBase64, strlen('data:image/png;base64,'));
            }

            $blogImage = base64_decode($blogImgBase64, true);
            $authorImage = base64_decode($authorImgBase64, true);

            if ($blogImage === false || $authorImage === false) {
                return response()->json(['error' => 'Invalid Base64 encoding'], 400);
            }

            $blog = new Blog();
            $blog->blog_title = $validatedData['blog_title'];
            $blog->blog_sub_title = $validatedData['blog_sub_title'];
            $blog->blog_description = $validatedData['blog_description'];
            $blog->blog_img = $blogImage; // Store as BLOB
            $blog->author_name = $validatedData['author_name'];
            $blog->author_img = $authorImage; // Store as BLOB
            $blog->created_by = Auth::id();
            $blog->active_yn = $request->input('active_yn');
            $blog->save();

            return response()->json([
                'message' => 'Blog created successfully!',
                'blog_id' => $blog
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



    // public function getActiveBlogs()
    // {
    //     $blogs = Blog::all();
    //     if ($blogs->isEmpty()) {
    //         return response()->json([
    //             'message' => 'Blogs not found'
    //         ], 404);
    //     }

    //     // Convert blog images to base64 format before sending response
    //     $blogs = $blogs->map(function ($blog) {
    //         return [
    //             'id' => $blog->id,
    //             'blog_title' => $blog->blog_title,
    //             'blog_sub_title' => $blog->blog_sub_title,
    //             'blog_description' => $blog->blog_description,
    //             'blog_img' => $blog->blog_img ? 'data:' . $blog->blog_img_type . ';base64,' . base64_encode($blog->blog_img) : null,
    //             'blog_img_name' => $blog->blog_img_name,
    //             'blog_img_type' => $blog->blog_img_type,
    //             'author_name' => $blog->author_name,
    //             'author_img' => $blog->author_img ? 'data:' . $blog->author_img_type . ';base64,' . base64_encode($blog->author_img) : null,
    //             'author_img_name' => $blog->author_img_name,
    //             'author_img_type' => $blog->author_img_type,
    //             'created_by' => $blog->created_by,
    //             'active_yn' => $blog->active_yn,
    //             'created_at' => $blog->created_at,
    //             'updated_at' => $blog->updated_at,
    //         ];
    //     });
        
    //     return response()->json([
    //         'blogs' => $blogs
    //     ], 200);
    // }

    public function getActiveBlogs()
    {
        $blogs = Blog::all();

        // Return a 404 if no blogs are found
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
                'blog_img' => $blog->blog_img ? 
                    'data:' . $blog->blog_img_type . ';base64,' . base64_encode($blog->blog_img) : null,
                'blog_img_name' => $blog->blog_img_name,
                'blog_img_type' => $blog->blog_img_type,
                'author_name' => $blog->author_name,
                'author_img' => $blog->author_img ? 
                    'data:' . $blog->author_img_type . ';base64,' . base64_encode($blog->author_img) : null,
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
            'blog_img' => $blog->blog_img ? 'data:' . $blog->blog_img_type . ';base64,' . base64_encode($blog->blog_img) : null,
            'author_name' => $blog->author_name,
            'author_img' => $blog->author_img ? 'data:' . $blog->author_img_type . ';base64,' . base64_encode($blog->author_img) : null,
            'created_by' => $blog->created_by,
            'active_yn' => $blog->active_yn,
            'created_at' => $blog->created_at,
            'updated_at' => $blog->updated_at,
        ]);
    }


    // public function updateBlog(Request $request, $id)
    // {
    //     try {
    //         // Validate input data
    //         $validatedData = $request->validate([
    //             'blog_title' => 'required|string|max:255',
    //             'blog_sub_title' => 'required|string|max:255',
    //             'blog_description' => 'required|string',
    //             'author_name' => 'required|string',
    //         ]);

    //         // Find the blog entry
    //         $blog = Blog::find($id);

    //         if (!$blog) {
    //             return response()->json([
    //                 'error' => 'Blog not found.',
    //             ], 404);
    //         }

    //         // Process base64 images (only update if new images are provided)
    //         $blogImage = $request->blog_img ? $this->saveBase64Image($request->blog_img) : $blog->blog_img;
    //         $authorImage = $request->author_img ? $this->saveBase64Image($request->author_img) : $blog->author_img;

    //         // Update blog details
    //         $blog->blog_title = $validatedData['blog_title'];
    //         $blog->blog_sub_title = $validatedData['blog_sub_title'];
    //         $blog->blog_description = $validatedData['blog_description'];
    //         $blog->blog_img = $blogImage;
    //         $blog->author_name = $validatedData['author_name'];
    //         $blog->author_img = $authorImage;
    //         $blog->updated_by = Auth::id();
    //         $blog->active_yn = $request->input('active_yn');
    //         $blog->save();

    //         return response()->json([
    //             'message' => 'Blog updated successfully!',
    //             'blog' => $blog,
    //         ], 200);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'error' => 'Validation failed.',
    //             'details' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'An error occurred during blog update.',
    //             'details' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function updateBlog(Request $request, $id)
{
    try {
        // Validate input data
        $validatedData = $request->validate([
            'blog_title' => 'required|string|max:255',
            'blog_sub_title' => 'required|string|max:255',
            'blog_description' => 'required|string',
            'author_name' => 'required|string',
            'blog_img' => 'nullable|string',  // Base64 string expected (optional)
            'author_img' => 'nullable|string', // Base64 string expected (optional)
        ]);

        // Find the blog entry
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'error' => 'Blog not found.',
            ], 404);
        }

        if ($request->has('blog_img')) {
            $blogImgBase64 = $request->blog_img;
            $blogImgBase64 = preg_replace('/[^a-zA-Z0-9\/+%=]/', '', $blogImgBase64);

            if (strpos($blogImgBase64, 'data:image/png;base64,') === 0) {
                $blogImgBase64 = substr($blogImgBase64, strlen('data:image/png;base64,'));
            }

            if (!preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $blogImgBase64)) {
                return response()->json(['error' => 'Invalid Base64 encoding for blog image'], 400);
            }

            $blogImage = base64_decode($blogImgBase64, true);
            if ($blogImage === false) {
                return response()->json(['error' => 'Failed to decode Base64 blog image'], 400);
            $blog->blog_img = $blogImage;
            }
        }

        if ($request->has('author_img')) {
            $authorImgBase64 = $request->author_img;
            
            // Sanitize the Base64 string by removing unwanted characters (spaces, newlines)
            $authorImgBase64 = preg_replace('/[^a-zA-Z0-9\/+%=]/', '', $authorImgBase64);
            
            // Debugging: Log the sanitized author image
            \Log::info("Sanitized Author Image Base64: ".$authorImgBase64);

            // Remove the 'data:image/png;base64,' prefix if present
            if (strpos($authorImgBase64, 'data:image/png;base64,') === 0) {
                $authorImgBase64 = substr($authorImgBase64, strlen('data:image/png;base64,'));
            }

            if (!preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $authorImgBase64)) {
                return response()->json(['error' => 'Invalid Base64 encoding for author image'], 400);
            }

            $authorImage = base64_decode($authorImgBase64, true);
            if ($authorImage === false) {
                return response()->json(['error' => 'Failed to decode Base64 author image'], 400);
            }
            
            $blog->author_img = $authorImage; // Update author image as BLOB
        }

        // Update blog details
        $blog->blog_title = $validatedData['blog_title'];
        $blog->blog_sub_title = $validatedData['blog_sub_title'];
        $blog->blog_description = $validatedData['blog_description'];
        $blog->author_name = $validatedData['author_name'];
        $blog->updated_by = Auth::id();
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
        \Log::error('Blog update error: '.$e->getMessage());
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
