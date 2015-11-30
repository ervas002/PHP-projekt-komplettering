<?php

Class Comment{

    public $commentId;
    public $postId;
    public $author;
    public $content;

    public function __construct($commentId, $postId, $author, $content){
        $this->commentId = $commentId;
        $this->postId = $postId;
        $this->author = $author;
        $this->content = $content;
    }

}