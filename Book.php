
<?php

class Book{

private $id;
private $title;
private $author;
private $category;
private $publishYear;
private $isAvailable;

function __construct($id, $title,$author,$category,$publishYear) {
  if (empty($id) || empty($title) || empty($author) || empty($category)) {
    throw new Exception("All fields required.");
}

if (!is_numeric($id) || $id <= 0) {
  throw new Exception("Invalid book ID.");
}

if (!is_numeric($publishYear) || $publishYear < 0 || $publishYear > date("Y")) {
    throw new Exception("Invalid year.");
}
    $this->id = $id;
    $this->title=$title;
    $this->author = $author;
    $this->category=$category;
    $this->publishYear=$publishYear;
    $this->isAvailable=true;
  }

  public function borrow(){
if($this->isAvailable){
        $this->isAvailable=false;
        return true;
    }
    else{
        return false;
    }

  }
  public function returnBook(){
    if($this->isAvailable==false){

    $this->isAvailable=true;
    return true;

    }    
    return false;
    
  }
  public function getInfo(){

  echo "Title : ".$this->title . "\nAuthor : ".$this->author . "\nCategory : ". $this->category ."\nPublish Date : " . $this->publishYear ."\nAvailable : " .($this->isAvailable ? "true" : "false") . "\n";

  }

  public function getTitle(){
return $this->title;
  }

  public function getId(){
    return $this->id;
  }

  public function getCategory(){
    return $this->category;

  }

  public function toArray(){
    return [
      'id' => $this->id,
      'title' => $this->title,
      'author' => $this->author,
      'category' => $this->category,
      'publishYear' => $this->publishYear,
      'isAvailable' => $this->isAvailable
    ];
  }

  public static function fromArray($data){
    $book = new Book(
      $data['id'],
      $data['title'],
      $data['author'],
      $data['category'],
      $data['publishYear']
    );

    if (isset($data['isAvailable']) && $data['isAvailable'] == false) {
      $book->borrow();
    }

    return $book;
  }


}
?>