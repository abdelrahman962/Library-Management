<?php

class Member{
private $id;
private $name;
private $email;
private $borrowedBooks=[];
function __construct($id, $name,$email) {
 if (empty($id) || empty($name) || empty($email)) {
            throw new Exception("All fields required.");
        }

        if (!is_numeric($id) || $id <= 0) {
            throw new Exception("Invalid member ID.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address.");
        }
    $this->id = $id;
    $this->name=$name;
    $this->email = $email;   
  }

public function borrowBook(Book $book){

if ($book->borrow()){
    $this->borrowedBooks[]=$book;
    return true;
}

return false;
}
public function returnBook(Book $book){

foreach($this->borrowedBooks as $k=>$b){
    if($book==$b){
        $book->returnBook();
        unset($this->borrowedBooks[$k]);
        return true;
    }
}
return false;
}
public function getBorrowedBooks(){
    return $this->borrowedBooks;

}

public function getId(){
    return $this->id;
}

public function toArray(){
    // Collect only the borrowed book IDs so the member can be saved as simple JSON data.
    $borrowedBookIds = [];

    // Convert each borrowed Book object into its ID.
    foreach ($this->borrowedBooks as $book) {
        $borrowedBookIds[] = $book->getId();
    }

    // Return the member data in array form for storage or serialization.
    return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'borrowedBookIds' => $borrowedBookIds
    ];
}

public static function fromArray($data, $bookMap = []){
    // Recreate the member object from saved array data.
    $member = new Member($data['id'], $data['name'], $data['email']);

    $member->borrowedBooks = [];

    // If borrowed book IDs exist, restore each matching Book object from the map.
    if (isset($data['borrowedBookIds']) && is_array($data['borrowedBookIds'])) {
        foreach ($data['borrowedBookIds'] as $bookId) {
            if (isset($bookMap[$bookId])) {
                // Mark the book as borrowed in the current session and attach it to the member.
                $book = $bookMap[$bookId];
                $book->borrow();
                $member->borrowedBooks[] = $book;
            }
        }
    }

    // Return the rebuilt Member object.
    return $member;
}


}


?>