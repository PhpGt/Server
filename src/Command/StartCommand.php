<?php
namespace Gt\Server\Command;

use Gt\Cli\Argument\ArgumentValueList;
use Gt\Cli\Command\Command;
use Gt\Cli\Parameter\NamedParameter;
use Gt\Cli\Parameter\Parameter;
use Gt\Cli\Stream;
use Gt\Daemon\Process;

class StartCommand extends Command {
	public function run(ArgumentValueList $arguments = null):void {
		$goPath = implode(DIRECTORY_SEPARATOR, [
			"vendor",
			"phpgt",
			"webengine",
			"go.php",
		]);
		if(!file_exists($goPath)) {
			$this->writeLine(
				"Error: Current directory is not a WebEngine project",
				Stream::ERROR
			);
			return;
		}

		$bind = $arguments->get("bind", "0.0.0.0");
		$port = $arguments->get("port", 8080);

		$docRoot = "www";
		if(!is_dir($docRoot)) {
			mkdir($docRoot);
		}

		$cmd = ["php"];

		if($arguments->contains("debug")) {
			array_push($cmd, "-dzend_extension=xdebug.so");
		}

		array_push($cmd, ...["-S", "$bind:$port", "-t", $docRoot, $goPath]);
		$this->writeLine("Executing: " . implode(" ", $cmd));
		$process = new Process(...$cmd);
		$process->exec();

		do {
			$output = $process->getOutput();
			$error = $process->getErrorOutput();

			if(!empty($output)) {
				$this->write($output);
			}

			if(!empty($error)) {
				$this->write($error);
			}

			usleep(250000); // 1/4 second
		}
		while($process->isRunning());

		$this->writeLine("Server process ended.");
	}

	public function getName():string {
		return "start";
	}

	public function getDescription():string {
		return "Start the inbuilt web server";
	}

	/** @return  NamedParameter[] */
	public function getRequiredNamedParameterList():array {
		return [];
	}

	/** @return  NamedParameter[] */
	public function getOptionalNamedParameterList():array {
		return [
			new NamedParameter("debug"),
		];
	}

	/** @return  Parameter[] */
	public function getRequiredParameterList():array {
		return [];
	}

	/** @return  Parameter[] */
	public function getOptionalParameterList():array {
		return [
			new Parameter(
				true,
				"port",
				"p"
			),
			new Parameter(
				true,
				"bind",
				"b"
			),
		];
	}
}
