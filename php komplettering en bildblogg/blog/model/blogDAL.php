<?php

require_once 'database/db.php';
require_once 'blogpost.php';
require_once 'comment.php';

Class BlogDAL{

    private $blogpostsArray = array();

    public function __construct(){
        $this->fillPostsArray();
    }

    public function getBlogpostArray(){
        return $this->blogpostsArray;
    }

    private function fillPostsArray(){
        $blogposts = $this->getAllPosts();
        $comments = $this->getAllComments();

        foreach($blogposts as $post){
            foreach($comments as $comment){
                if($comment->postId == $post->postId){
                    $post->commentsArray[] = $comment;
                }
            }

        }

        $this->blogpostsArray = $blogposts;
    }

    private function getAllPosts(){
        $posts = array();
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $result = $dbConnection->query("CALL getAllPosts()");
            while($row = $result->fetch_object()){
                $posts[] = new Blogpost($row->postId, $row->author, $row->title, $row->content, $row->picture);
            }
            $dbConnection->close();
        }
        return $posts;
    }

    private function getAllComments(){
        $comments = array();
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $result = $dbConnection->query("CALL getAllComments()");
            while($row = $result->fetch_object()){
                $comments[] = new Comment($row->commentId, $row->postId, $row->author, $row->content);
            }
            $dbConnection->close();
        }
        return $comments;
    }

    public function addPost($username, $title, $content, $picture){
        $db = new DB();
        //$picture = $picture['name'];
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $dbConnection->query("CALL addPost('$username', '$title', '$content', '$picture')");
            $dbConnection->close();
        }
    }

    public function addComment($postId, $username, $content){
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $dbConnection->query("CALL addComment('$postId', '$username', '$content')");
            $dbConnection->close();
        }
    }

    public function editComment($commentId, $content){
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            echo $content;
            $dbConnection->query("CALL editComment('$commentId', '$content')");
            $dbConnection->close();
        }
    }

    public function removeComment($commentId){
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $dbConnection->query("CALL removeComment('$commentId')");
            $dbConnection->close();
        }
    }

    public function editPost($postId, $title, $content, $picture){
        $db = new DB();
        if($db->isSuccess()){
            $dbConnection = $db->getCon();
            $dbConnection->query("CALL editPost('$postId', '$title', '$content', '$picture')");
            $dbConnection->close();
        }
    }
}