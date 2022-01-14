<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 0);

class Divisors
{
    public $factor = [];

    public function __construct($num)
    {
        $this->num = $num;
    }

    // count number of divisors of a number
    public function countDivisors()
    {
        if (1 == $this->num) {
            return 1;
        }

        $this->_primefactors();

        $array_primes = array_count_values($this->factor);
        echo 'ARRAY_PRIMES: [', implode(', ', $array_primes), ']', PHP_EOL;
        $divisors = 1;
        foreach ($array_primes as $power) {
            $divisors *= ++$power;
            echo 'DIVISORS: ', $divisors, PHP_EOL;
        }

        return $divisors;
    }

    // prime factors decomposer
    private function _primefactors()
    {
        $this->factor = [];
        $run = true;
        while ($run && @$this->factor[0] != $this->num) {
            $run = $this->_getFactors();
        }
    }

    // get all factors of the number
    private function _getFactors()
    {
        if (1 == $this->num) {
            return;
        }
        $root = ceil(sqrt($this->num)) + 1;
        $i = 2;
        while ($i <= $root) {
            if (0 == $this->num % $i) {
                $this->factor[] = $i;
                $this->num = $this->num / $i;

                return true;
            }
            ++$i;
        }
        $this->factor[] = $this->num;

        return false;
    }
} // our class ends here

// $example = new Divisors(4567893421);
$example = new Divisors(665280);
echo $example->countDivisors();
