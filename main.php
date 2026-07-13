<?php
require "Book.php";
require "Member.php";
require "Library.php";

$dataFile = __DIR__ . DIRECTORY_SEPARATOR . "library_data.json";

$lib = Library::loadFromJson($dataFile);

$bookSeeds = [
    new Book(1, "To Kill a Mockingbird", "Harper Lee", "Fiction", 1960),
    new Book(2, "Pride and Prejudice", "Jane Austen", "Classic", 1813),
    new Book(3, "The Hobbit", "J.R.R. Tolkien", "Fantasy", 1937),
    new Book(4, "The Great Gatsby", "F. Scott Fitzgerald", "Adventure", 1925)
];

foreach ($bookSeeds as $book) {
    if ($lib->getBookById($book->getId()) === null) {
        $lib->addBook($book);
    }
}

$memberSeeds = [
    new Member(1, "Abdalrahman", "abdalrahman@gmail.com"),
    new Member(2, "Omar", "omar@gmail.com")
];

foreach ($memberSeeds as $member) {
    if ($lib->getMemberById($member->getId()) === null) {
        $lib->addMember($member);
    }
}

$member1 = $lib->getMemberById(1);

$lib->displayBooks();

echo "\nBorrowing workflow:\n";
$bookToBorrow = $lib->searchBookByTitle("The Hobbit");
if ($bookToBorrow && $member1->borrowBook($bookToBorrow)) {
    echo "Book borrowed successfully.\n";
} else {
    echo "Book could not be borrowed.\n";
}

echo "\nMember borrowed books:\n";
foreach ($member1->getBorrowedBooks() as $borrowedBook) {
    $borrowedBook->getInfo();
}

echo "\nReturning :\n";
if ($bookToBorrow && $member1->returnBook($bookToBorrow)) {
    echo "Book returned successfully.\n";
} else {
    echo "Book could not be returned.\n";
}

echo "\nMember borrowed books after return:\n";
foreach ($member1->getBorrowedBooks() as $borrowedBook) {
    $borrowedBook->getInfo();
}


$bookToBorrow=$lib->searchBookByCategory("Fiction");
$member2=$lib->getMemberById(2);
if ($bookToBorrow && $member2->borrowBook($bookToBorrow)) {
    echo "Book borrowed successfully.\n";
} else {
    echo "Book could not be borrowed.\n";
}
// echo "\nReturning :\n";
// if ($bookToBorrow && $member2->returnBook($bookToBorrow)) {
//     echo "Book returned successfully.\n";
// } else {
//     echo "Book could not be returned.\n";
// }
$lib->saveToJson($dataFile);




?>