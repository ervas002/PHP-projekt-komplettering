<?php

Class BlogView{

    private $isLoggedIn;
    private $username;

    const NEW_POST = "NEW_POST";
    const NEW_POST_TITLE = "NEW_POST_TITLE";
    const NEW_POST_CONTENT = "NEW_POST_CONTENT";
    const NEW_POST_PICTURE = "NEW_POST_PICTURE";

    const NEW_COMMENT = "NEW_COMMENT";
    const NEW_COMMENT_POST_ID = "NEW_COMMENT_POST_ID";
    const NEW_COMMENT_CONTENT = "NEW_COMMENT_CONTENT";

    const EDIT_POST = "EDIT_POST";
    const EDIT_POST_ID = "EDIT_POST_ID";
    const EDIT_POST_TITLE = "EDIT_POST_TITLE";
    const EDIT_POST_CONTENT= "EDIT_POST_CONTENT";
    const EDIT_POST_PICTURE = "EDIT_POST_PICTURE";

    const EDIT_COMMENT = "EDIT_COMMENT";
    const EDIT_COMMENT_ID = "EDIT_COMMENT_ID";
    const EDIT_COMMENT_CONTENT = "EDIT_COMMENT_CONTENT";

    const DELETE_COMMENT = "DELETE_COMMENT";
    const DELETE_COMMENT_ID = "DELETE_COMMENT_ID";

    const NO_ACTION = "NO_ACTION";

    private $errors = "";

    public function __construct($isLoggedIn, $username){
        $this->isLoggedIn = $isLoggedIn;
        if($username != ""){
            $this->username = $username;
        }
    }

    public function getHTML($postsArray, $picStoragePath){

        $postDivs = $this->getPostDivs($postsArray, $picStoragePath);

        if($this->isLoggedIn){
            $newPostForm = $this->getSubmitPostForm();
        }else{
            $newPostForm = "<a href='sign-in.php'>Logga in ifall du vill kunna posta inlägg och kommentera!</a>";
        }

        return "
            <!DOCTYPE html>
				<html lang='en'>
				  <head>
				    <meta charset='utf-8'>
				    <title>bloggen</title>
				    <link rel='stylesheet' href='css/bootstrap/css/bootstrap.css'>
				    <link rel='stylesheet' href='css/bloggen.css'>
				    <script src='javascript/jquery-2.1.0.js'></script>
				    <script src='javascript/js.js'></script>
				  </head>
				  <body>
				    <div class='pageDiv'>
				        <a href='sign-in.php'>Logga ut</a>
				        <p id='errors' hidden>$this->errors</p>
                        $newPostForm
                        $postDivs
                    </div>
				  </body>
				</html>";
    }

    private function getPostDivs($postsArray, $picStoragePath){
        $postDivs = "";
        foreach($postsArray as $post){
            $comments = "";
            $commentNum = 1;
            foreach($post->commentsArray as $comment){
                $comments .= $this->getCommentHTML($comment, $commentNum);
                $commentNum++;
            }
            $commentForm = "";

            $editButton = "";
            $editPostButtonId = $post->postId . "enablePostEditButton";
            $editPostDiv = "";
            $editPostDivId = $post->postId . "editPostDiv";

            if($this->isLoggedIn){
                $commentForm = $this->getSubmitCommentForm($post-> postId);
                if($post->author == $this->username){
                    $editButton = "<button class='enablePostEditButton' id=$editPostButtonId type='button'> Redigera inlägg </button>";
                    $editPostForm = "<form method='post' role='form' enctype='multipart/form-data'>
                        <input type='hidden' name='" . self::EDIT_POST . "'>
                        <input type='hidden' name='" . self::EDIT_POST_ID . "' value='$post->postId'>
                        <input type='text' name='" . self::EDIT_POST_TITLE . "' id='" . self::EDIT_POST_TITLE . "' placeholder='Ny titel (max 100 tecken)' value=''>
                        <input type='file' name='" . self::EDIT_POST_PICTURE . "' id='" . self::EDIT_POST_PICTURE . "'>
                        <textarea name='" . self::EDIT_POST_CONTENT . "'>$post->content</textarea>
                        <input type='submit' value='Spara ändring'/>
                        </form>";
                    $editPostDiv = "<div id='$editPostDivId' style='display:none'>$editPostForm</div>";
                }
            }
            $postDivs .= "<div id='$post->postId' class='postDiv'>
                            <h3>$post->author</h3>
                            <p>$post->title</p>
                            $editButton
                            $editPostDiv
                            <br>
                            <a href='$picStoragePath$post->picture'> <img src='$picStoragePath$post->picture' alt='Upload goes here'></a>
                            <p>$post->content</p>
                            $commentForm
                            $comments
                            </div>";
        }
        return $postDivs;
    }

    private function getCommentHTML($comment, $commentNum){
        $commentHTML = "";
        $editButton = "";
        $editButtonId = $comment->commentId . "enableEditButton";
        $editCommentDiv = "";
        $editCommentForm = "";
        $editCommentDivId = $comment->commentId . "editDiv";
        $removeCommentForm = "";
        //Sätt unikt id på varje formular, ha med samma id till knappen på något sätt så att js kan strippa knappen på id och använda för att visa formuläret.
        if($comment->author == $this->username){
            $editButton = "<button class='enableEditButton' id=$editButtonId type='button'> Redigera kommentar </button>";
            $editCommentForm = "<form method='post' role='form'>
                        <input type='hidden' name='" . self::EDIT_COMMENT . "'>
                        <input type='hidden' name='" . self::EDIT_COMMENT_ID . "' value='$comment->commentId'>
                        <textarea name='" . self::EDIT_COMMENT_CONTENT . "'>$comment->content</textarea>
                        <input type='submit' value='Spara ändring'/>
                        </form>";
            $removeCommentForm = "<form method='post' role='form'>
                        <input type='hidden' name='" . self::DELETE_COMMENT . "'>
                        <input type='hidden' name='" . self::DELETE_COMMENT_ID . "' value='$comment->commentId'>
                        <input type='submit' value='Ta bort kommentar'/>
                        </form>";
            $editCommentDiv .= "<div id='$editCommentDivId' style='display:none'>
                                $removeCommentForm
                                $editCommentForm</div>";
        }
        $commentHTML .= "<div id='$comment->commentId' class='commentDiv'>
                        $editButton
                        <h4>$comment->author</h4>
                        <p>#$commentNum</p>
                        <p>$comment->content</p>
                        $editCommentDiv
                </div>";
        return $commentHTML;
    }

    private function getSubmitPostForm(){
        return "<form id='newPostForm' method='post' role='form' enctype='multipart/form-data'>
                <input type='hidden' name='" . self::NEW_POST . "'>
                <input type='text' name='" . self::NEW_POST_TITLE . "' id='" . self::NEW_POST_TITLE . "' placeholder='Titel (max 100 tecken)' value=''>
                <input type='file' name='" . self::NEW_POST_PICTURE . "' id='" . self::NEW_POST_PICTURE . "'>
                <input type='text' name='" . self::NEW_POST_CONTENT . "' id='" . self::NEW_POST_CONTENT . "' placeholder='Innehåll (max 200 tecken)'>
                <input type='submit' id='submitPostButton' name='submitPostButton'/>
            </form>";
    }

    private function getSubmitCommentForm($postId){
        return "<form class='newCommentDiv' id='newCommentForm' method='post' role='form'>
                <input type='hidden' name='" . self::NEW_COMMENT . "'>
                <input type='hidden' name='" . self::NEW_COMMENT_POST_ID . "' value='$postId'>
                <textarea class='newCommentTextarea' name='" . self::NEW_COMMENT_CONTENT . "' id='" . self::NEW_COMMENT_CONTENT . "' placeholder='Ny kommentar (max 200 tecken)'></textarea>
                <input type='submit' id='submitPostButton' name='submitPostButton'/>
            </form>";
    }

    public function isPostback(){
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST'){
            return true;
        } else {
            return false;
        }
    }

    public function whatAction(){
        if(isset($_POST[self::NEW_POST])){
            unset($_POST[self::NEW_POST]);
            return self::NEW_POST;
        }else if(isset($_POST[self::NEW_COMMENT])){
            unset($_POST[self::NEW_COMMENT]);
            return self::NEW_COMMENT;
        }else if(isset($_POST[self::EDIT_COMMENT])){
            unset($_POST[self::EDIT_COMMENT]);
            return self::EDIT_COMMENT;
        }else if(isset($_POST[self::EDIT_POST])) {
            unset($_POST[self::EDIT_POST]);
            return self::EDIT_POST;
        }else if(isset($_POST[self::DELETE_COMMENT])){
            unset($_POST[self::DELETE_COMMENT]);
            return self::DELETE_COMMENT;
        }else{
            return self::NO_ACTION;
        }
    }

    public function getDataFromPOST($postLocation){
        $data = "";
        if(isset($_POST[$postLocation])){
            $data .= $_POST[$postLocation];
            unset($_POST[$postLocation]);
        }
        return $data;
    }

    public function getNewUpload($postLocation){
        $upload = "";
        if($_FILES[$postLocation]){
            $upload = $_FILES[$postLocation];
            unset($_FILES[$postLocation]);
        }
        return $upload;
    }

    public function addErrors($error){
        $this->errors .= $error;
    }
}