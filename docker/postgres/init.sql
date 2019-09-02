CREATE USER flashcards;
ALTER ROLE flashcards WITH PASSWORD 'secret';

CREATE DATABASE flashcards_dev OWNER flashcards;
GRANT ALL PRIVILEGES ON DATABASE flashcards_dev TO flashcards;

CREATE DATABASE flashcards_test OWNER flashcards;
GRANT ALL PRIVILEGES ON DATABASE flashcards_test TO flashcards;
