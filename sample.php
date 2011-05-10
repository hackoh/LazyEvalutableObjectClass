<?php
require 'LazyEvalutableObject.php';
class Test extends LazyEvalutableObject {
	public $omase = 'san';
	public $tokyo = 'kyoto';
	public $one_time;
	public $normal_property = 100;
	public function __construct() {
		$this->addLazyEvalutionObserver('omase', array($this, 'omase'));
		$this->addLazyEvalutionObserver('tokyo', array($this, 'tokyo'));
		$this->addLazyEvalutionObserver('one_time', array($this, 'one_time'));
	}
	public function omase($property) {
		echo sprintf("hello! property is %s\n", $property);
	}
	public function tokyo($property) {
		echo sprintf("hello! property is %s\n", $property);
		return 'osaka';
	}
	public function one_time($property) {
		echo sprintf("hello! property is %s\n", $property);
		$this->one_time = rand(0, 10);
		return $this->one_time;
	}
}

$test = new Test();

echo sprintf("\$test->omase = %s\n", $test->omase);

echo sprintf("\$test->tokyo = %s\n", $test->tokyo);

echo sprintf("\$test->one_time = %s\n", $test->one_time);
echo sprintf("\$test->one_time = %s\n", $test->one_time);
