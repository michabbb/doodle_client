<?php

use Causal\DoodleClient\Domain\Model\Poll;

class ApiTestRead extends init {

	public function test_cookies() {
		$this->assertTrue(is_array($this->cookies));
		$this->assertArrayHasKey('worker', $this->cookies);
		$this->assertArrayHasKey('value', $this->cookies['worker']);
		$this->assertArrayHasKey('Token', $this->cookies);
		$this->assertArrayHasKey('value', $this->cookies['Token']);
		$this->assertEquals($this->doodleClient->getToken(), $this->cookies['Token']['value']);
		$this->assertArrayHasKey('DoodleIdentification', $this->cookies);
		$this->assertArrayHasKey('value', $this->cookies['DoodleIdentification']);
		$this->assertArrayHasKey('DoodleAuthentication', $this->cookies);
		$this->assertArrayHasKey('value', $this->cookies['DoodleAuthentication']);
		$this->assertRegExp('/^[0-9\-]+$/', $this->cookies['DoodleAuthentication']['value']);
	}

	/**
	 * @depends test_cookies
	 */
	public function test_myPolls() {
		$myPolls = $this->doodleClient->getPersonalPolls();
		$this->assertTrue(is_array($myPolls));
		/** @var Poll $Poll */
		foreach ($myPolls as $Poll) {
			print_r($Poll);
			$this->assertInstanceOf(Poll::class, $Poll);
			$this->assertObjectHasAttribute('id', $Poll);
			$this->assertObjectHasAttribute('type', $Poll);
			$this->assertObjectHasAttribute('title', $Poll);
			$participants = $Poll->getParticipants();
			print_r($participants);
		}
	}



}
 