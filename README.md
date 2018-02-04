# beanstalkd_winphp
Windows Command Line client for Beanstalkd, using PHP CLI, to make Windows CLI workers


	php bsworker.php --help
	WinWorker for Beanstalkd version 1.0.0

	Run a Windows CLI beanstalkd client worker

	Syntax: [options] bshost bsqueue bsport
	Arguments:
	    bshost string IP/hostname address of the beanstalkd server (required)
	    bsqueue string name of the beanstalkd queue (required)
	    bsport number TCP port of the beanstalkd server (default: 11300)
	SubCommands:
	    --help Shows the help message.
	    --version Shows the version.
	    -i Reads the command arguments and options interactively.
