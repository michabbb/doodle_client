<?php

class init extends \PHPUnit_Framework_TestCase{

    /**
     * @var \Pimple\Container
     */
    protected $container;

    protected $username;
    protected $password;

	/**
	 * @var \Causal\DoodleClient\Client
	 */
	public $doodleClient;

	public $cookies = [];

    public function setUp() {
        global $container;
        $this->container = $container;
        $this->username = $container['config']['auth']['username'];
        $this->password = $container['config']['auth']['password'];
		$this->doodleClient = new Causal\DoodleClient\Client($this->username, $this->password);
		$this->doodleClient->setCookiePath('/tmp/');
		$this->doodleClient->connect();
		$this->login();
    }

	private function login() {
		$this->assertNotEmpty($this->doodleClient->getToken());
		$this->cookies = $this->doodleClient->getCookies();
	}

}