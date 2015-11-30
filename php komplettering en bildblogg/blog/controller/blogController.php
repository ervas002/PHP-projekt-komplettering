<?php

require_once 'blog/view/blogView.php';
require_once 'blog/model/blogDAL.php';
require_once 'blog/model/blogModel.php';

Class BlogController{

    private $blogView;
    private $blogModel;
    private $blogDAL;
    private $username;

    private $isLoggedIn;

    public function __construct($isLoggedIn, $username){
        if($isLoggedIn){
            $this->isLoggedIn = $isLoggedIn;
            $this->username = $username;
        }else{
            $this->isLoggedIn = $isLoggedIn;
            $this->username = "";
        }

        $this->blogView = new BlogView($isLoggedIn, $this->username);
        $this->blogModel = new BlogModel();
        $this->blogDAL = new BlogDAL();
    }

    //Returnerar HTML för sidan
    public function getHTML(){

        $postsArray = $this->blogDAL->getBlogpostArray();

        if($this->isLoggedIn){
            //inloggad html
            return $this->blogView->getHTML($postsArray, BlogModel::STORAGE_LOCATION);
        }else{
            //utlogggad html, får inte redigera
            return $this->blogView->getHTML($postsArray, BlogModel::STORAGE_LOCATION);
        }
    }

    //Kontrollerar vad som trigggade sidladdningen och tar hand om det.
    // Skulle möjligtvis kunna bryta ut det som finns i switch-satsen.
    public function checkPostback(){
        if($this->blogView->isPostback()){
            switch($this->blogView->whatAction()){
                case BlogView::NEW_POST:
                    //hämta post från view
                    $newPostTitle = $this->blogView->getDataFromPOST(BlogView::NEW_POST_TITLE);
                    $newPostContent = $this->blogView->getDataFromPOST(BlogView::NEW_POST_CONTENT);
                    $newUpload = $this->blogView->getNewUpload(BlogView::NEW_POST_PICTURE);
                    //verifiera i model, felmeddelande tillbaka till view annars
                    if($this->blogModel->validateNewPost($newPostTitle, $newPostContent) && $this->checkRightUser()){
                        //om ok, skicka till DAL för att lagra
                        $newName = $this->blogModel->storePicture($newUpload);
                        //$this->blogDAL->addPost($this->username, $newPostTitle, $newPostContent);
                        $this->blogDAL->addPost($this->username, $this->blogModel->stripSpecialChars($newPostTitle), $this->blogModel->stripSpecialChars($newPostContent), $newName);
                    }else{
                        $this->blogView->addErrors("Det skedde ett fel när ditt inlägg skulle publiceras.");
                    }
                    break;
                case BlogView::EDIT_POST:
                    //hämta post från view
                    $editPostTitle = $this->blogView->getDataFromPOST(BlogView::EDIT_POST_TITLE);
                    $editPostContent = $this->blogView->getDataFromPOST(BlogView::EDIT_POST_CONTENT);
                    $editUpload = $this->blogView->getNewUpload(BlogView::EDIT_POST_PICTURE);
                    //verifiera i model, felmeddelande tillbaka till view annars
                    if($this->blogModel->validateNewPost($editPostTitle, $editPostContent) && $this->checkRightUser()){
                        //om ok, skicka till DAL för att lagra
                        $newName = $this->blogModel->storePicture($editUpload);
                        $this->blogDAL->editPost($this->blogView->getDataFromPOST(BlogView::EDIT_POST_ID), $this->blogModel->stripSpecialChars($editPostTitle), $this->blogModel->stripSpecialChars($editPostContent), $newName);
                    }else{
                        $this->blogView->addErrors("Det skedde ett fel när ditt inlägg skulle redigeras.");
                    }
                    break;
                case BlogView::NEW_COMMENT:
                    $newCommentContent = $this->blogView->getDataFromPOST(BlogView::NEW_COMMENT_CONTENT);
                    $newCommentParentPostId = $this->blogView->getDataFromPOST(BlogView::NEW_COMMENT_POST_ID);
                    if($this->blogModel->validateNewComment($newCommentContent)  && $this->checkRightUser()){
                        $this->blogDAL->addComment($newCommentParentPostId, $this->username, $this->blogModel->stripSpecialChars($newCommentContent));
                    }else{
                        $this->blogView->addErrors("Det skedde ett fel när din kommentar skulle publiceras.");
                    }
                    break;
                case BlogView::EDIT_COMMENT:
                    //echo "Det här e content!: " . $this->blogView->getEditedCommentContent();
                    $editCommentContent = $this->blogView->getDataFromPOST(BlogView::EDIT_COMMENT_CONTENT);
                    $editCommentId = $this->blogView->getDataFromPOST(BlogView::EDIT_COMMENT_ID);
                    if($this->blogModel->validateNewComment($editCommentContent) && $this->checkRightUser()){
                        $this->blogDAL->editComment($editCommentId, $this->blogModel->stripSpecialChars($editCommentContent));
                    }else{
                        $this->blogView->addErrors("Det skedde ett fel när din kommentar skulle redigeras.");
                    }
                    break;
                case BlogView::DELETE_COMMENT:
                    if($this->checkRightUser()){
                        $this->blogDAL->removeComment($this->blogView->getDataFromPOST(BlogView::DELETE_COMMENT_ID));
                    }else{
                        $this->blogView->addErrors("Det skedde ett fel när din kommentar skulle tas bort.");
                    }
                    $this->blogView->addErrors("Det skedde ett fel när din kommentar skulle tas bort.");
                    break;
                case BlogView::NO_ACTION:
                    break;
            }
            //De nya inläggen postas inte under samma sidladdning, så den här är nödvändig tillsvidare
            header('Location: bloggen.php');
        }
    }

    //Kontrollerar så att användaren faktiskt är den dom utger sig för att vara.
    public function checkRightUser(){
        $session = new Session();
        if($session->getUsername() === $this->username){
            return true;
        }
        return false;
    }
}