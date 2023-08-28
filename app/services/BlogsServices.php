<?php

namespace App\Services;

use App\Models\Posts;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BlogsServices
{
    public function creatingAuthor(string $name, string $email, string $password): bool
    {
        User::create([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
        ]);

        return true;
    }

    public function publishingBlogPosts(string $title, string $content, string $publishedAt, int $authorID)
    {
        $this->validateAuthor($authorID);

        $postBlogs = Posts::create([
            "titles" => $title,
            "content" => $content,
            "published_at" => $publishedAt,
            "author_id" => $authorID,
        ]);

        return $postBlogs;
    }

    /**
     * list all blog posts
     *
     * @return void
     */
    public function listBlogPosted()
    {
        $blogs = Posts::all(["id", "titles", "content", "published_at"]);

        return $blogs;
    }

    /**
     * list bblog post details selected
     *
     * @param integer $postID
     * @return void
     */
    public function listBlogPostedDetails(int $postID)
    {
        $blogDetails = DB::selectOne("SELECT Posts.titles, Posts.content,
                                   Posts.published_at, PostBlogComments.user_email,
                                   PostBlogComments.comments
                                   FROM Posts LEFT JOIN PostBlogComments
                                   ON Posts.id = PostBlogComments.post_id
                                   WHERE Posts.id = ?", [$postID]);

        return [
            "titles" => $blogDetails->titles,
            "content" => $blogDetails->content,
            "published_at" => $blogDetails->published_at,
        ];
    }

    public function listBlogByAuthor(int $authorID)
    {
        $blogByauthor = DB::select("SELECT Posts.titles, Posts.content,
                                    Posts.published_at, users.name AS authorName
                                    FROM Posts
                                    INNER JOIN users ON Posts.author_id = users.id
                                    WHERE users.id = ?", [$authorID]);

        return $blogByauthor;
    }

    /**
     * update blog post * title, content and published_at
     *
     * @param string $title
     * @param string $content
     * @param string $publishedAt
     * @param integer $postID
     * @return void
     */
    public function updateBlogPost(string $title, string $content, string $publishedAt, int $authorID, int $postID)
    {
        $post = $this->validateIfAuthorCreatedThisPost($authorID, $postID);

        $updatePosts = Posts::where("id", $post)->update([
            "titles" => $title,
            "content" => $content,
            "published_at" => $publishedAt,
        ]);

        return $updatePosts;
    }

    /**
     * validate to check if the author is existing
     *
     * @param [type] $authorID
     * @return void
     */
    public function validateAuthor($authorID)
    {
        $author = User::where("id", $authorID)->first();

        if (is_null($author)) {

            throw new Exception("Author not found");
        }

        return $author;
    }

    /**
     * validate to check if the post has been published by this author
     *
     * @param integer $authorID
     * @param integer $postID
     * @return void
     */
    public function validateIfAuthorCreatedThisPost(int $authorID, int $postID)
    {
        $postID = Posts::where([["id", $postID], ["author_id", $authorID]])->first();

        if (!$postID) {
            throw new Exception("Post not found created by author id $authorID");
        }

        return $postID->id;
    }
}
