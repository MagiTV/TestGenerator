USE `referat_questions`;
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (1, 81493, 60);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (2, 81493, 78);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (3, 81493, 98);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (9, 81493, 13);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (4, 81646, 93);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (5, 81646, 48);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (8, 81646, 86);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (9, 81646, 13);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (3, 81640, 86);
INSERT INTO testTaken (test_id, taker_fn, result)
VALUES (1, 81640, 99);

UPDATE presence
SET present = "yes"
WHERE fn = 81493
    AND topic = 101;
UPDATE presence
SET present = "yes"
WHERE fn = 81646
    AND topic = 123;
UPDATE presence
SET present = "yes"
WHERE fn = 81646
    AND topic = 147;


UPDATE topicRating
SET rating = 43
WHERE owner_fn = 81646;
UPDATE topicRating
SET rating = 73
WHERE owner_fn = 81640;
UPDATE topicRating
SET rating = 0
WHERE owner_fn = 81493;

UPDATE tests
SET results_sum = 159, times_taken = 2
WHERE id = 1;
UPDATE tests
SET results_sum = 78, times_taken = 1
WHERE id = 2;
UPDATE tests
SET results_sum = 184, times_taken = 2
WHERE id = 3;
UPDATE tests
SET results_sum = 93, times_taken = 1
WHERE id = 4;
UPDATE tests
SET results_sum = 48, times_taken = 1
WHERE id = 5;
UPDATE tests
SET results_sum = 86, times_taken = 2
WHERE id = 8;
UPDATE tests
SET results_sum = 26, times_taken = 2
WHERE id = 9;