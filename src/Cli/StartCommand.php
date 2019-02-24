<?php
namespace Gt\Server\Cli;

use Gt\Cli\Argument\ArgumentValueList;
use Gt\Cli\Command\Command;
use Gt\WebEngine\Lifecycle;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Response;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;

class StartCommand extends Command {
	public function __construct() {
		$this->setName("start");
		$this->setDescription("Start the development webserver");

		$this->setOptionalParameter(
			true,
			"bind",
			"b",
			"0.0.0.0"
		);
		$this->setOptionalParameter(
			true,
			"port",
			"p",
			"8080"
		);
		$this->setOptionalParameter(
			true,
			"docroot",
			"t",
			"path/to/docroot"
		);
	}

	public function run(ArgumentValueList $arguments = null):void {
//		$stream = new ThroughStream();
		$loop = Factory::create();

		$server = new HttpServer(function (ServerRequestInterface $request) {
//			$lifecycle = new \G
			$lifecycle = new Lifecycle();
			$response = $lifecycle->start(false);
			return $response;

//			return new Response(
//				200,
//				array(
//					'Content-Type' => 'text/plain'
//				),
//				"Hello World!\n"
//			);
		});

		$socket = new SocketServer(8080, $loop);
		$server->listen($socket);

		$loop->run();
	}
}