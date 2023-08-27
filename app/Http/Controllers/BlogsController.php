<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAuthorRequest;
use App\Http\Requests\CreatePublishBlogPosts;
use App\Http\Requests\UpdateBlogPostsRequest;
use App\Models\Posts;
use App\Services\BlogsServices;

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
            $this->service->updateBlogPost($request->title, $request->content, $request->published_at, $request->author_id, $postID);

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
