<?php

Class BlogModel{

    const STORAGE_LOCATION = 'userPicUploads/';

    public function validateNewPost($title, $content){
        if(strlen($title) > 100 || strlen($title) < 1 || strlen($content) > 200 || strlen($content) < 1){
            return false;
        }
        return true;
    }

    public function validateNewComment($content){
        if(strlen($content) > 200 || strlen($content) < 1){
            return false;
        }
        return true;
    }

    //Ser till att det inte kommer med några konstigheter i HTML för att undvika XSS.
    public function stripSpecialChars($text){
        return htmlspecialchars($text);
    }

    //Byter ut tecken som annars skulle bråkat.
    private function stripFilename($oldFilename){
        return preg_replace('/[^a-z0-9\.]/i','_', $oldFilename);
    }

    //Itererar över mappen med bilder för att kontrollera ifall det redan finns en fil med samma namn.
    private function fileNameExists($currentName){
        $fileIterator = new FilesystemIterator(self::STORAGE_LOCATION);
        while($fileIterator->valid()){
            if($fileIterator->getFilename() === $currentName){
                return true;
            }
            $fileIterator->next();
        }
        return false;
    }

    //Lagrar den uppladdade bilden.
    public function storePicture($picture){

        $newName = $this->stripFilename($picture['name']);
        $extraString = 1;
        while($this->fileNameExists($newName)){
            //Riktigt ful lösning men det fungerar tills vidare
            $newName .= $extraString++;
        }
        move_uploaded_file($picture['tmp_name'], self::STORAGE_LOCATION . $newName);
        return $newName;
    }
}