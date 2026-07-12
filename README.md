Library Management System
=========================

Small PHP command-line library demo that manages books and members and saves state to JSON.


Files
- Book.php — `Book` model (validation, borrow/return, serialization).
- Member.php — `Member` model (borrowed books, serialization/deserialization).
- Library.php — `Library` container, search/save/load logic.
- main.php — Example runner script that demonstrates basic usage.
- library_data.json — Persisted library state (created/updated at runtime).

JSON format
- `books`: array of book objects with `id`, `title`, `author`, `category`, `publishYear`, `isAvailable`.
- `members`: array of members. By default members store `borrowedBookIds` (array of book ids).

Note
- The project stores member borrowed books by ID to avoid duplicating full book records in JSON. If you prefer full book details under each member, update `Member::toArray()` and `Member::fromArray()` accordingly.