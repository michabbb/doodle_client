<?php

use Causal\DoodleClient\Domain\Model\Poll;

class ApiTestWrite extends init {

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
	 * @throws Exception
	 */
	public function test_create_doodle() {
		$date = new DateTime();
		list($start_date, $end_date) = $this->x_week_range($date->format('Y-m-d'));
		echo 'WEEK STARTS AT: ' . $start_date . "\n";
		echo 'WEEK ENDS   AT: ' . $end_date . "\n";
		$dates = [];
		for ($i = 0; $i <= 1; $i++) {
			$next_date = date('Ymd', strtotime($start_date . ' +' . $i . ' day'));
			echo 'NEXT DAY: ' . $next_date . "\n";
			$dates[$next_date] = [
				$next_date.'0000-' . $next_date . '0100',
				$next_date.'0100-' . $next_date . '0200',
				$next_date.'0200-' . $next_date . '0300',
				$next_date.'0900-' . $next_date . '1000',
				$next_date.'1000-' . $next_date . '1100',
				$next_date.'1200-' . $next_date . '1300',
				$next_date.'1300-' . $next_date . '1400',
				$next_date.'1400-' . $next_date . '1500',
				$next_date.'1500-' . $next_date . '1600',
				$next_date.'1600-' . $next_date . '1700',
				$next_date.'1700-' . $next_date . '1800',
				$next_date.'1800-' . $next_date . '1900',
				$next_date.'1900-' . $next_date . '2000',
				$next_date.'2000-' . $next_date . '2100',
				$next_date.'2100-' . $next_date . '2200',
				$next_date.'2200-' . $next_date . '2300',
				$next_date.'2300-' . $next_date . '2400',
			];
		}
		/** @var Poll $newPoll */
		$newPoll = $this->doodleClient->createPoll(
			[
				'type'             => 'date',
				'title'            => 'test api',
				'location'         => 'here',
				'description'      => 'test descr',
				'name'             => 'Guru Destiny',
				'email'            => $this->username,
				'country'          => 'DE',
				'columnConstraint' => 6,
				'dates'            => $dates
			]
		);
		print_r($newPoll);
		echo 'link to new poll: ' . $newPoll->getPublicUrl() . "\n";
		echo 'poll id: ' . $newPoll->getId() . "\n";
	}

	private function x_week_range($date) {
		$ts    = strtotime($date);
		$start = (date('w', $ts) === 0) ? $ts : strtotime('today', $ts);

		return [date('Ymd', $start),
				date('Ymd', strtotime('next sunday', $start))];
	}

	public function test_delete_all_polls() {
		$myPolls = $this->doodleClient->getPersonalPolls();
		$this->assertTrue(is_array($myPolls));
		/** @var Poll $Poll */
		foreach ($myPolls as $Poll) {
			try {
				echo 'Delete Poll: '.$Poll->getId()."\n";
				$ret = $this->doodleClient->deletePoll($Poll);
				$this->assertTrue($ret);
			} catch (Exception $e) {
				throw new \RuntimeException($e);
			}
			print_r($ret);
		}
	}

	public function test_deleteParticipant() {
		$myPolls = $this->doodleClient->getPersonalPolls();
		foreach ($myPolls as $Poll) {
			$participants = $Poll->getParticipants();
			foreach ($participants as $participant) {
				echo 'Delete Participant: '.$participant->getId() . ' at ' . $Poll->getTitle() . "\n";
				$this->assertTrue($this->doodleClient->deleteParticipant($Poll, $participant->getId()));
			}
		}
	}
}
 