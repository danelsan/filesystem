<?php

use danelsan\filesystem\File;
use danelsan\filesystem\Folder;

class FileTest extends PHPUnit_Framework_TestCase {
	private $file;
	private $name;
	private $path_file;
	private $root_dir;
	
	public function setUp() {
		$root =	dirname(__FILE__) . DIRECTORY_SEPARATOR;
		$nameFile = 'tests.test';
		$this->name = $nameFile;
		$this->root_dir = $root;
		$this->path_file = $root.$nameFile;
		
		$this->file = new File( $this->path_file );
	}

	public function testCreateReadFile() {
		try {
			$this->file->open('r');
			$true = false;
		} catch ( \Exception $e ) {
			$true = true;
		}
		$this->assertTrue( $true );
		
		$this->file->open( 'w' );
		try {
			$this->file->write( 'pidppo' );
			$true = true;
		} catch ( \Exception $e ) {
			$true = false;
		}
		$this->assertTrue( $true );
	}
	
	public function testDeleteFile() {
		$del = 	$this->file->delete();
		$this->assertTrue( $del );
	}
	
		
	public function testWriteFile() {
		try {
			$this->file->open('aa');
			$true = false;
		} catch ( \Exception $e ) {
			$true = true;
		}
		$this->assertTrue( $true );
		
		try {
			$this->file->open('a');
			$this->file->write('pippo');
			$true = true;
		} catch ( \Exception $e ) {
			$true = false;
		}
		$this->assertTrue( $true );
		
		$this->file->open();
		$read = $this->file->read();
		$this->assertEquals('pippo', $read);
		
		try {
			$this->file->open('a');
			$this->file->write(' o pluto');
			$true = true;
		} catch ( \Exception $e ) {
			$true = false;
		}
		$this->assertTrue( $true );
		
		$this->file->open();
		$read = $this->file->read();
		
		$this->assertEquals('pippo o pluto', $read);
		try {
			$this->file->open('w');
			$this->file->write('pluto');
			$true = true;
		} catch ( \Exception $e ) {
			$true = false;
		}
		$this->assertTrue( $true );
		
		$this->file->open();
		$read = $this->file->read();
		$this->assertEquals('pluto', $read);
	}
	
	public function testNameFile() {
		$this->assertEquals('tests.test',  $this->file->name() );
	}
	
	public function testCopyFile() {
		$newFile = new Folder( $this->root_dir. 'pippo'  );
		$this->file->copyTo( $newFile, true ) ;
		$file = $this->file->parentFolder()->getFolder('pippo')->getFile('tests.test');
		$file->open(); 
		$this->file->open();
		$this->assertEquals($file->read(),  $this->file->read() );
		$newFile->delete();
	}
	

	public function testDeleteErrorFile() {
		// Delete OK
		$del = 	$this->file->delete();
		$this->assertTrue( $del );
		// Error if not a file
		$delerr = 	$this->file->delete();
		$this->assertFalse( $delerr );
	}
}
