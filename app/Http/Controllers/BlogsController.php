<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\CreatePublishBlogPosts;
use App\Http\Requests\UpdateBlogPostsRequest;
use App\Models\Posts;
use App\Models\User;
use App\Services\BlogsServices;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BlogsController extends Controller
{
    public function __construct(protected BlogsServices $service)
    {
    }

    /**
     * Creating author of the blog
     *
     * @param CreateAuthorRequest $request
     * @return void
     */
    public function creatingAuthor(CreateAuthorRequest $request)
    {
        try {
            $this->service->creatingAuthor($request->name, $request->email, $request->password);

            return response()->json(["Author Created successfully"]);

        } catch (\Exception $e) {
            [$message, $statusCode, $exceptionCode] = getHttpMessageAndStatusCodeFromException($e);

            return response()->json([
                "message" => $message,
            ], $statusCode);
        }
    }

    public function loginAuthor(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|string"
        ]);

        if ($validation->fails()){
            return response()->json(["errors" => $validation->errors()->all()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return \response()->json(["errors" => ["User not found"]], Response::HTTP_NOT_FOUND);
        }

        if (!Hash::check($request->password, $user->password)) {
            return \response()->json(["errors" => ["Invalid credentials"]], Response::HTTP_FORBIDDEN);
        }
        Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);
        $token = Auth::user()->createToken('token')->plainTextToken;

        return \response()->json([
            "author_id" => $user->id,
            "token" => $token,
        ]);
    }

    /**
     * creating to publish blog post
     *
     * @param CreatePublishBlogPosts $request
     * @return void
     */
    public function publishBlogPosts(CreatePublishBlogPosts $request)
    {
        try {
            $this->service->publishingBlogPosts($request->title, $request->content, $request->published_at, $request->author_id);

            return response()->json(["Blog Posts Created successfully"]);

        } catch (\Exception $e) {
            [$message, $statusCode, $exceptionCode] = getHttpMessageAndStatusCodeFromException($e);

            return response()->json([
                "message" => $message,
            ], $statusCode);
        }
    }

    /**
     * list blog posted
     *
     * @return void
     */
    public function listBlogPosted()
    {
        try {

            $result = $this->service->listBlogPosted();

            return response()->json($result);

        } catch (\Exception $e) {
            [$message, $statusCode, $exceptionCode] = getHttpMessageAndStatusCodeFromException($e);

            return response()->json([
                "message" => $message,
            ], $statusCode);
        }
    }

    /**
     * liist blog posted details
     *
     * @param integer $postID
     * @return void
     */
    public function listBlogPostedDetails(int $postID)
    {
        try {
            $result = $this->service->listBlogPostedDetails($postID);

            return response()->json($result);
        } catch (\Exception $e) {
            [$message, $statusCode, $exceptionCode] = getHttpMessageAndStatusCodeFromException($e);

            return response()->json([
                "message" => $message,
            ], $statusCode);
        }
    }

    public function listBlogByAuthor(int $authorID)
    {
        try {
            $result = $this->service->listBlogByAuthor($authorID);

            return response()->json($result);
        } catch (\Exception $e) {
            [$message, $statusCode, $exceptionCode] = getHttpMessageAndStatusCodeFromException($e);

            return response()->json([
                "message" => $message,
            ], $statusCode);
        }
    }

    /**
     * update blog post details
     *
     * @param UpdateBlogPostsRequest $request
     * @param integer $postID
     * @return void
     */
    public function updateBlogPost(UpdateBlogPostsRequest $request, int $postID)
    {
        try {
            $this->service->updateBlogPost($request->titles, $request->content, $request->published_at, $request->author_id, $postID);

            return response()->json(["Blog Post Updated successfully"]);

        } catch (\Exception $e) {
            [$message, $statusCode, $exceptionCode] = getHttpMessageAndStatusCodeFromException($e);

            return response()->json([
                "message" => $message,
            ], $statusCode);
        }
    }

    public function deleteBlogPost(int $authorID, int $postID)
    {
        $post = $this->service->validateIfAuthorCreatedThisPost($authorID, $postID);

        $deletePosts = Posts::where("id", $post)->delete();

        return $deletePosts;
    }
}
