#!/usr/bin/php
<?php
	function help()
	{
		echo <<<HELP
Usage: build.php [options] [project-configuration-file.inc.php] [metaconfiguration.xml]

Possible options:

	--only-containers:
		update (or rewrite if combined with --force) containers only.
	
	--no-schema:
		do not generate DB schema.
	
	--no-integrity-check:
		do not try to test classes integrity.
	
	--no-schema-check:
		do not try to diff DB schemas.
	
	--drop-stale-files:
		remove found stale files.
	
	--force:
		regenerate all files.
	
	--dry-run:
		print the results of building without actually changing any files.
	
	--no-color:
		do not use colored output.
	
	--with-enum-check-ref-integrity:
		check enumeration reference integrity [EXPERIMENTAL:for the real nerds].
		
	--puml:
		just create uml diagramm and die.

HELP;
		exit(1);
	}
	
	function init()
	{
		Hesper\Core\Base\Assert::isTrue(defined('PATH_CLASSES'), 'constant PATH_CLASSES must be defined');

		if (!defined('HESPER_META_BUSINESS_DIR_NAME'))
			define('HESPER_META_BUSINESS_DIR_NAME', 'Business');

		if (!defined('HESPER_META_DAO_DIR'))
			define(
				'HESPER_META_DAO_DIR',
				PATH_CLASSES.'DAO'.DIRECTORY_SEPARATOR
			);
		
		if (!defined('HESPER_META_BUSINESS_DIR'))
			define(
				'HESPER_META_BUSINESS_DIR',
				PATH_CLASSES.HESPER_META_BUSINESS_DIR_NAME.DIRECTORY_SEPARATOR
			);
		
		if (!defined('HESPER_META_PROTO_DIR'))
			define(
				'HESPER_META_PROTO_DIR',
				PATH_CLASSES.'Proto'.DIRECTORY_SEPARATOR
			);

		define('HESPER_META_AUTO_DIR', PATH_CLASSES.'Auto'.DIRECTORY_SEPARATOR);
		
		if (!defined('HESPER_META_AUTO_BUSINESS_DIR'))
			define(
				'HESPER_META_AUTO_BUSINESS_DIR',
				HESPER_META_AUTO_DIR.HESPER_META_BUSINESS_DIR_NAME.DIRECTORY_SEPARATOR
			);
		
		define(
			'HESPER_META_AUTO_PROTO_DIR',
			HESPER_META_AUTO_DIR
			.'Proto'.DIRECTORY_SEPARATOR
		);
		
		if (!defined('HESPER_META_AUTO_DAO_DIR'))
			define(
				'HESPER_META_AUTO_DAO_DIR',
				HESPER_META_AUTO_DIR
				.'DAO'.DIRECTORY_SEPARATOR
			);
		
		if (!defined('EXT_CLASS'))
			define('EXT_CLASS','.php');

		if (!is_dir(HESPER_META_DAO_DIR))
			mkdir(HESPER_META_DAO_DIR, 0755, true);
		
		if (!is_dir(HESPER_META_AUTO_DIR))
			mkdir(HESPER_META_AUTO_DIR, 0755, true);
		
		if (!is_dir(HESPER_META_AUTO_BUSINESS_DIR))
			mkdir(HESPER_META_AUTO_BUSINESS_DIR, 0755);
			
		if (!is_dir(HESPER_META_AUTO_PROTO_DIR))
			mkdir(HESPER_META_AUTO_PROTO_DIR, 0755);
		
		if (!is_dir(HESPER_META_AUTO_DAO_DIR))
			mkdir(HESPER_META_AUTO_DAO_DIR, 0755);
		
		if (!is_dir(HESPER_META_BUSINESS_DIR))
			mkdir(HESPER_META_BUSINESS_DIR, 0755, true);
		
		if (!is_dir(HESPER_META_PROTO_DIR))
			mkdir(HESPER_META_PROTO_DIR, 0755, true);
	}
	
	function stop($message = null)
	{
		fwrite(STDERR, $message."\n\n");
		
		help();
	}
	
	// paths
	$pathConfig = $pathMeta = null;
	
	// switches
	$metaForce = $metaOnlyContainers = $metaNoSchema =
	$metaNoSchemaCheck = $metaDropStaleFiles =
	$metaNoIntegrityCheck = $metaDryRun = 
	$metaCheckEnumerationRefIntegrity = $metaNoColor = $createPUML = false;
	
	$args = $argv;
	array_shift($args);
	
	if ($args) {
		foreach ($args as $arg) {
			if ($arg[0] == '-') {
				switch ($arg) {
					case '--only-containers':
						$metaOnlyContainers = true;
						break;
					
					case '--no-schema':
						$metaNoSchema = true;
						break;
					
					case '--no-integrity-check':
						$metaNoIntegrityCheck = true;
						break;
					
					case '--no-schema-check':
						$metaNoSchemaCheck = true;
						break;
					
					case '--drop-stale-files':
						$metaDropStaleFiles = true;
						break;
					
					case '--force':
						$metaForce = true;
						break;
					
					case '--dry-run':
						$metaDryRun = true;
						break;
					
					case '--no-color':
						$metaNoColor = true;
						break;
					
					case '--with-enum-check-ref-integrity':
						$metaCheckEnumerationRefIntegrity = true;
						break;
					
					case '--puml':
						$createPUML = true;
						
						break;
					default:
						stop('Unknown switch: '.$arg);
				}
			} else {
				if (file_exists($arg)) {
					$extension = pathinfo($arg, PATHINFO_EXTENSION);

					if($extension == 'php') {
						$pathConfig = $arg;
					} elseif($extension == 'xml') {
						$pathMeta = $arg;
					} else {
						stop('Unknown path: '.$arg);
					}
				} else {
					stop('Unknown option: '.$arg);
				}
			}
		}
	}

	if(!$pathConfig) {
		stop("Path to config.php is not defined!");
	}
	if(!$pathMeta) {
		stop("Path to meta.xml is not defined!");
	}

	require $pathConfig;

	if (
		isset($_SERVER['TERM'])
		&& (
			$_SERVER['TERM'] == 'xterm'
			|| $_SERVER['TERM'] == 'xterm-256color'
			|| $_SERVER['TERM'] == 'linux'
		)
		&& !$metaNoColor
	) {
		$out = new \Hesper\Meta\Console\ColoredTextOutput();
	} else {
		$out = new \Hesper\Meta\Console\TextOutput();
	}
	
	$out = new \Hesper\Meta\Console\MetaOutput($out);

	if ($pathMeta && $pathConfig) {
		init();
		
		$out->
			newLine()->
			infoLine('Hesper-'.HESPER_VERSION.': MetaConfiguration builder.', true)->
			newLine();
		
		try {
			$meta =
				\Hesper\Meta\Entity\MetaConfiguration::me()->
				setOutput($out)->
				load(HESPER_META.'internal.xml', false);
			
			$out->info('Known internal classes: ');
			foreach ($meta->getClassList() as $class) {
				$out->info($class->getName().', ', true);
			}
			$out->infoLine("that's all.")->newLine();
			
			$meta->
				setDryRun($metaDryRun)->
				load($pathMeta)->
				setForcedGeneration($metaForce);
			
			if ($createPUML) {
				$pumlFile = HESPER_META_AUTO_DIR.DIRECTORY_SEPARATOR."puml.txt";
				
				file_put_contents($pumlFile, $meta->makePUML());
				
				$out->infoLine('puml saved to '.$pumlFile);
				
				exit();
			}
				
			
			if ($metaOnlyContainers) {
				$meta->buildContainers();
			} else {
				$meta->
					buildClasses()->
					buildContainers();
				
				if (!$metaNoSchema)
					$meta->buildSchema();
				
				if (!$metaNoSchemaCheck)
					$meta->buildSchemaChanges();
			}
			
			$meta->checkForStaleFiles($metaDropStaleFiles);
			
			$out->newLine()->info('Trying to compile all known classes... ');
			
			\Hesper\Main\Util\ClassUtils::preloadAllClasses();
			
			$out->infoLine('done.');
			
			if ($metaCheckEnumerationRefIntegrity)
				$meta->setWithEnumerationRefIntegrityCheck(true);
			
			if (!$metaNoIntegrityCheck)
				$meta->checkIntegrity();
		} catch (\Hesper\Core\Exception\BaseException $e) {
			$out->
				newLine()->
				errorLine($e->getMessage(), true)->
				newLine()->
				logLine(
					$e->getTraceAsString()
				);
		}
	} else {
		$out->getOutput()->resetAll()->newLine();
		
		stop('Can not continue.');
	}
	
	$out->getOutput()->resetAll();
	$out->newLine();
