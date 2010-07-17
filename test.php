<?php
/**
 * Self-Referential Aptitude Test Solver
 * The test: http://www.cs.berkeley.edu/~lorch/personal/self-ref.html
 * The Reddit post: http://www.reddit.com/r/programming/comments/cqk6d/berkeley_programming_professor_posted_an/
 * 
 * Copyright 2010, David Hulbert
 * Licensed under the MIT license.
 * 
 * TODO
 *     implement Test::next_iteration() instead of using Test::randomize_answers()
 *     fully implement question 2
 *     fully implement question 13
 *     think about questions 6 and 17
 *     go unto the next iteration as soon as a question returns false
 *     prioritize questions (eg 20 and 13 first, 18 last)
 *     display overall progress
 *     split the 9.54e+13 possible solitions into chunks
 *     port to JavaScript and run distributedly
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);

define('A', 1);
define('B', 2);
define('C', 3);
define('D', 4);
define('E', 5);


class Test {
	
	public $best_so_far = 0;

	public $best_so_far_answers = '';

	public $best_so_far_correct = '';

	public $total_correct = 0;
		
	public $answers = array();

	public function next_iteration() {
		// TODO: implement
		$this->answers[19]++;
		// loop through $answers backwards and if answer == 6, answer = A, prev answer++
	}

	private function answer_to_chr($a) {
		return chr(64 + $a);
	}

	private function rnd() {
		return (int) rand(1, 5);
	}

	public function randomize_answers() {
		foreach ($this->answers as &$a) {
			$a = $this->rnd();
		}		
	}

	public function all_answers_as_string() {
		$r = '';
		foreach ($this->answers as $a) {
			$r .= $this->answer_to_chr($a) . '  ';
		}
		return $r;
	}

	private function a($question) {
		// get answer to a question
		return $this->answers[$question - 1];
	}

	public function ask() {
		$this->total_correct = 0;
		$r = '';
		for ($i = 1; $i <= 20; $i++) {
			if (method_exists($this, 'q'.$i)) {
				$correct = call_user_func(array($this, 'q'.$i), $this->a($i));
				$r .= $correct ? '1' : '0';
				$r .= '  ';

				if ($correct) {
					$this->total_correct++;
				}
			} else {
				$r .= '.  ';
			}
		}
		
		if ($this->total_correct > $this->best_so_far) {
			$this->best_so_far = $this->total_correct;
			$this->best_so_far_answers = $this->all_answers_as_string();
			$this->best_so_far_correct = $r;
		}

		return $r . ' Total: ' . $this->total_correct;
	}

	private function total_number_of($answer) {
		$count = 0;
		foreach ($this->answers as $a) {
			if ($a == $answer) {
				$count++;
			}
		}
		return $count;
	}	


	// the questions
		

	private function q1($answer) {
		/* 1. The first question whose answer is B is question
		(A)    1 
		(B)    2 
		(C)    3 
		(D)    4 
		(E)    5 
		*/
		$first_b_pos = null;

		foreach ($this->answers as $q => $a) {
			if ($a == B) {
				$first_b_pos = 1 + $q;
				break;
			}
		}
		return $answer === $first_b_pos;
	}
	
	private function q2($answer) {
		/* 2. The only two consecutive questions with identical answers are questions
		(A)    6 and 7 
		(B)    7 and 8 
		(C)    8 and 9 
		(D)    9 and 10 
		(E)    10 and 11 
		*/
		// TODO: make sure no other consecutive questions have identical answers
		switch ($answer) {
			case A:
				return $this->a(6) == $this->a(7);
			case B:
				return $this->a(6) == $this->a(7);
			case C:
				return $this->a(6) == $this->a(7);
			case D:
				return $this->a(6) == $this->a(7);
			case E:
				return $this->a(6) == $this->a(7);
		}
		
	}
	
	private function q3($answer) {
		/* 3. The number of questions with the answer E is
		(A)    0 
		(B)    1 
		(C)    2 
		(D)    3 
		(E)    4 
		*/
		$es = 0;

		foreach ($this->answers as $q => $a) {
			if ($a == E) {
				$es++;
			}
		}
		return $answer - 1 === $es;
	}
	
	private function q4($answer) {
		/* 4. The number of questions with the answer A is
		(A)    4 
		(B)    5 
		(C)    6 
		(D)    7 
		(E)    8 
		*/
		$as = 0;

		foreach ($this->answers as $q => $a) {
			if ($a == A) {
				$as++;
			}
		}
		return $answer + 3 === $as;
	}


	
	private function q5($answer) {
		/* 5. The answer to this question is the same as the answer to question
		(A)    1 
		(B)    2 
		(C)    3 
		(D)    4 
		(E)    5 
		*/
		switch ($answer) {
			case A:
				return $this->a(1) == A;
			case B:
				return $this->a(2) == B;
			case C:
				return $this->a(3) == C;
			case D:
				return $this->a(4) == D;
			case E:
				return $this->a(5) == E;
		}
		
	}
	
	
	private function q6($answer) {
		/* 6. The answer to question 17 is
		(A)    C 
		(B)    D 
		(C)    E 
		(D)    none of the above 
		(E)    all of the above 
		*/
		switch ($answer) {
			case A:
				return $this->a(17) == C;
			case B:
				return $this->a(17) == D;
			case C:
				return $this->a(17) == E;
			case D:
				return $this->a(17) == A || $this->a(17) == B;
			case E:
				// TODO: work out if this is needed
				return $this->a(7) == 'all of the above';
		}
		
	}
	
	
	private function q7($answer) {
		/* 7. Alphabetically, the answer to this question and the answer to the following question are
		(A)    4 apart 
		(B)    3 apart 
		(C)    2 apart 
		(D)    1 apart 
		(E)    the same 
		*/
		switch ($answer) {
			case A:
				return $this->a(8) == E;
			case B:
				return $this->a(8) == E;
			case C:
				return $this->a(8) == E || $this->a(8) == A;
			case D:
				return $this->a(8) == E || $this->a(8) == C;
			case E:
				return $this->a(8) == E;
		}
	}
	
	private function q8($answer) {
		/* 8. The number of questions whose answers are vowels is
		(A)    4 
		(B)    5 
		(C)    6 
		(D)    7 
		(E)    8  
		*/
		$vowels = 0;

		foreach ($this->answers as $q => $a) {
			if ($a == A || $a == E) {
				$vowels++;
			}
		}
		return $answer + 3 === $vowels;
	}


	private function q9($answer) {
		/* 9. The next question with the same answer as this one is question
		(A)    10 
		(B)    11 
		(C)    12 
		(D)    13 
		(E)    14 
		*/
		switch ($answer) {
			case A:
				return $this->a(10) == A;
			case B:
				return $this->a(11) == B;
			case C:
				return $this->a(12) == C;
			case D:
				return $this->a(13) == D;
			case E:
				return $this->a(14) == E;
		}
	}
	
	private function q10($answer) {
		/* 10. The answer to question 16 is
		(A)    D 
		(B)    A 
		(C)    E 
		(D)    B 
		(E)    C 
		*/
		switch ($answer) {
			case A:
				return $this->a(16) == D;
			case B:
				return $this->a(16) == A;
			case C:
				return $this->a(16) == E;
			case D:
				return $this->a(16) == B;
			case E:
				return $this->a(16) == C;
		}
	}

	
	private function q11($answer) {
		/* 11. The number of questions preceding this one with the answer B is
		(A)    0 
		(B)    1 
		(C)    2 
		(D)    3 
		(E)    4 
		*/
		$bs = 0;

		foreach ($this->answers as $q => $a) {
			if ($q == 10) {
				break;
			}
			if ($a == B) {
				$bs++;
			}
		}
		return $answer - 1 === $bs;
	}
	
	private function q12($answer) {
		/* 12. The number of questions whose answer is a consonant is
		(A)    an even number 
		(B)    an odd number 
		(C)    a perfect square 
		(D)    a prime 
		(E)    divisible by 5 
		*/
		$consonants = 0;

		foreach ($this->answers as $q => $a) {
			if ($a == B | $a == C | $a == D) {
				$consonants++;
			}
		}

		switch ($answer) {
			case A:
				return $consonants % 2 === 0;
			case B:
				return $consonants % 2 === 1;
			case C:
				return $consonants === 1 || $consonants === 4 || $consonants === 9 || $consonants === 16;
			case D:
				return in_array($consonants, array(2, 3, 5, 7, 11, 13, 17, 19));
			case E:
				return $consonants % 5 === 0;

		}
	}


	private function q13($answer) {
		/* 13. The only odd-numbered problem with answer A is
		(A)    9 
		(B)    11 
		(C)    13 
		(D)    15 
		(E)    17 
		*/
		// TODO: check its the *only* one
		switch ($answer) {
			case A:
				return $this->a(9) == A;
			case B:
				return $this->a(11) == A;
			case C:
				return $this->a(13) == A; // never true
			case D:
				return $this->a(15) == A;
			case E:
				return $this->a(16) == A;
		}
	}

	
	private function q14($answer) {
		/* 14. The number of questions with answer D is
		(A)    6 
		(B)    7 
		(C)    8 
		(D)    9 
		(E)    10 
		*/
		$ds = 0;

		foreach ($this->answers as $q => $a) {
			if ($a == B) {
				$ds++;
			}
		}
		return $answer + 5 === $ds;
	}

	private function q15($answer) {
		/* 15. The answer to question 12 is
		(A)    A 
		(B)    B 
		(C)    C 
		(D)    D 
		(E)    E 	
		*/
		return $this->a(12) == $answer;
	}

	private function q16($answer) {
		/* 116. The answer to question 10 is
		(A)    D 
		(B)    C 
		(C)    B 
		(D)    A 
		(E)    E 
		*/
		switch ($answer) {
			case A:
				return $this->a(10) == D;
			case B:
				return $this->a(10) == C;
			case C:
				return $this->a(10) == B;
			case D:
				return $this->a(10) == A;
			case E:
				return $this->a(10) == E;
		}
	}

	
	private function q17($answer) {
		/* 17. The answer to question 6 is
		(A)    C 
		(B)    D 
		(C)    E 
		(D)    none of the above 
		(E)    all of the above  
		*/
		switch ($answer) {
			case A:
				return $this->a(6) == C;
			case B:
				return $this->a(6) == D;
			case C:
				return $this->a(6) == E;
			case D:
				return $this->a(6) == A || $this->a(6) == B;
			case E:
				// TODO: work out if this is needed
				return $this->a(6) == 'all of the above';
		}
		
	}
	
	
	private function q18($answer) {
		/* 18. The number of questions with answer A equals the number of questions with answer
		(A)    B 
		(B)    C 
		(C)    D 
		(D)    E 
		(E)    none of the above 
		*/
		$as = $this->total_number_of(A);
		$bs = $this->total_number_of(B);
		$cs = $this->total_number_of(C);
		$ds = $this->total_number_of(D);
		$es = $this->total_number_of(E);

		switch ($answer) {
			case A:
				return $as === $bs;
			case B:
				return $as === $cs;
			case C:
				return $as === $ds;
			case D:
				return $as === $es;
			case E:
				return $as !== $bs && $as !== $cs && $as !== $ds && $as !== $es;
		}
	}

	
	private function q19($answer) {
		/* 19. The answer to this question is
		(A)    A 
		(B)    B 
		(C)    C 
		(D)    D 
		(E)    E 
		*/
		return true;
	}

		
	private function q20($answer) {
		/* 20. Standardized test : intelligence :: barometer :
		(A)    temperature (only) 
		(B)    wind-velocity (only) 
		(C)    latitude (only) 
		(D)    longitude (only) 
		(E)    all of the above 
		*/
		return $answer === E;
	}

		


}


$test = new Test;

$test->answers = array(A, A, A, A, A, A, A, A, A, A, A, A, A, A, A, A, A, A, A, A);

$best = 0;

$max = pow(5, 20);

for ($i = 0; $i < $max; $i++) {
	$test->randomize_answers();
	$answers = $test->all_answers_as_string();
	$correct = $test->ask();
//	print 'Answers: ' . $answers . "\n";
//	print 'Correct: ' . $correct . "\n\n";

	if ($test->best_so_far > $best) {
		$best = $test->best_so_far;
		print "\nBest ou of $i attempts:  $test->best_so_far correct\n";
		print 'Qu.    : 1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 16 17 18 19 20' . PHP_EOL;
		print 'Answers: ' . $test->best_so_far_answers . "\n";
		print 'Correct: ' . $test->best_so_far_correct . "\n";
		flush();
	}
}


