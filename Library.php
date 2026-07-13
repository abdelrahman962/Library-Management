<?php

class Library{
    private $members=[];
    private $books=[];
    


    public function addBook(Book $book){
       foreach ($this->books as $b) {
           if ($b->getId() == $book->getId()) {
               throw new Exception("Duplicate book ID.");
           }
       }
       $this->books[]=$book;

    }
    public function removeBook(Book $book){
    
        foreach($this->books as $k=>$b){
            if($book==$b){
                unset($this->books[$k]);
                return true;
            }
        }

        return false;
    }
  public function addMember(Member $member){
        foreach ($this->members as $mem) {
                if ($mem->getId() == $member->getId()) {
                        throw new Exception("Duplicate member ID.");
                }
        }
    $this->members[]=$member;
  }


public function searchBookByTitle($title){
    foreach($this->books as $b){
        if($b->getTitle()==$title){
            return $b;
        }
    }
    return NULL;

}
public function searchBookByCategory($category){
    
foreach($this->books as $b){
        if($b->getCategory()==$category){
            return $b;
        }
    }
    return NULL;
    
}

public function getBookById($id){
    foreach ($this->books as $book) {
        if ($book->getId() == $id) {
            return $book;
        }
    }

    return null;
}

public function getMemberById($id){
    foreach ($this->members as $member) {
        if ($member->getId() == $id) {
            return $member;
        }
    }

    return null;
}

public function displayBooks(){
    foreach($this->books as $b){
        print_r($b->getInfo());
        
    }
}

public function toArray(){
    $bookData = [];
    foreach ($this->books as $book) {
        $bookData[] = $book->toArray();
    }

    $memberData = [];
    foreach ($this->members as $member) {
        $memberData[] = $member->toArray();
    }

    return [
        'books' => $bookData,
        'members' => $memberData
    ];
}

public function saveToJson($filePath){
    $json = json_encode($this->toArray(), JSON_PRETTY_PRINT);
    if ($json === false) {
        throw new Exception("Failed to encode library data.");
    }

    file_put_contents($filePath, $json);
}

public static function loadFromJson($filePath){
    // Start with a fresh empty library instance.
    $library = new Library();

    // If the file does not exist, return the empty library.
    if (!file_exists($filePath)) {
        return $library;
    }

    // Read the raw JSON text from disk.
    $json = file_get_contents($filePath);
    if ($json === false || trim($json) === '') {
        return $library;
    }

    // Decode the JSON into an associative array.
    $data = json_decode($json, true);
    if (!is_array($data)) {
        throw new Exception("Invalid JSON library data.");
    }

    // Build a lookup table so members can reconnect to their borrowed books.
    $bookMap = [];
    if (isset($data['books']) && is_array($data['books'])) {
        foreach ($data['books'] as $bookData) {
            // Recreate each book object and store it in both the library and the map.
            $book = Book::fromArray($bookData);
            $library->books[] = $book;
            $bookMap[$book->getId()] = $book;
        }
    }

    // Recreate each member and restore its borrowed books using the map above.
    if (isset($data['members']) && is_array($data['members'])) {
        foreach ($data['members'] as $memberData) {
            $library->members[] = Member::fromArray($memberData, $bookMap);
        }
    }

    // Return the fully rebuilt library object.
    return $library;
}

}

?>