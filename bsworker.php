<?php
require __DIR__.'/vendor/autoload.php';

use Tarsana\Command\Command;
use Beanstalk\Client;

Class WinWorker extends Command {

    protected function init ()
    {
        $this->name('WinWorker for Beanstalkd')
             ->version('1.0.0')
             ->description('Run a Windows CLI beanstalkd client worker')
             ->syntax('bshost: string, bsqueue: string, bsport: (number: 11300)')
             ->describe('bshost', 'IP/hostname address of the beanstalkd server')
             ->describe('bsqueue', 'name of the beanstalkd queue')
             ->describe('bsport', 'TCP port of the beanstalkd server');
    }

    protected function execute()
    {
        $bscfg=Array(
			'host' => $this->args->bshost,
			'port' => $this->args->bsport,
        	);
        $beanstalk = new Client($bscfg);
		$beanstalk->connect();
        $this->console->line(sprintf("Connected to beanstalkd server [%s:%s]",$this->args->bshost,$this->args->bsport));
		$beanstalk->watch($this->args->bsqueue);

		while (true) {
		    $job = $beanstalk->reserve(); // Block until job is available.
		    // Now $job is an array which contains its ID and body:
		    // ['id' => 123, 'body' => '/path/to/cat-image.png']

		    // Processing of the job...
		    $output=Array();
		    $status=-1;
		    $lastline = exec($job['body'],$output,$status);

		    if ($status == 0) {
		        $beanstalk->delete($job['id']);
		    } else {
		        $beanstalk->bury($job['id']);
		    }
		}
		// When exiting i.e. on critical error conditions
		// you may also want to disconnect the consumer.
		// $beanstalk->disconnect();
	}

}

(new WinWorker)->run();

?>