<?php

Class Blogpost{

    public $postId;
    public $author;
    public $title;
    public $content;
    public $picture;
    public $commentsArray = array();

    public function __construct($postId, $author, $title, $content, $picture){
        $this->postId = $postId;
        $this->author = $author;
        $this->title = $title;
        $this->content = $content;
        $this->picture = $picture;
    }
}