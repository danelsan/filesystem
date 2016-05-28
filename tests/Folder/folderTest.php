<?php

use danelsan\filesystem\File;
use danelsan\filesystem\Folder;

class FolderTest extends PHPUnit_Framework_TestCase {
	
	private $folder;
	private $root_dir;
	private $newFolder;
	
	public function setUp() {
		$root =	dirname(__FILE__) . DIRECTORY_SEPARATOR. 'test1';
		$this->newFolder = dirname(__FILE__) . DIRECTORY_SEPARATOR. 'test2';
		$this->root_dir = $root;
		$this->folder = new Folder( $root );
	}

	public function testCreateFolder() {
		$this->assertEquals( $this->folder->exist(), true);
		
		$folder = $this->folder->parentFolder();
		$foldernew = $folder->addFolder('testnew');
		$foldernew->make();
		
		$this->assertEquals( $foldernew->exist(), true);
	}
	
	public function testListFolder() {	
		$list = $this->folder->childs();
		$this->assertEquals( is_array( $list ), true);
	}
	
	public function testRenameFolder() {	
		$test55 = $this->folder->rename( 'test55' );
		$this->assertTrue( $test55 );

		$this->assertTrue( $this->folder->exist() );
		$test55 = $this->folder->rename( 'test1' );
		$this->assertTrue( $test55 );
	}
	
	public function testMoveFolder() {
		$oldFolder = new Folder( $this->folder->path() );
		$folder2 = new Folder ( $this->newFolder );
		$this->folder->moveTo( $folder2 , true);
		
		$this->assertEquals( $this->folder->name() , 'test2' );
		$this->assertEquals( $oldFolder->exist() , false );
		$this->folder->moveTo($oldFolder );
		
	}
	
	public function testCopyFolder() {	
		$folder2 = new Folder ( $this->newFolder );
		$this->folder->copyTo( $folder2 , true);
		
		$this->assertEquals( count($this->folder->childs()), count($folder2->childs() ) );
		
		$folder2->delete();
	}
		
	public function testDeleteFolder() {	
		$this->assertEquals( $this->folder->exist(), true);
		
		$folder = $this->folder->parentFolder();
		$foldernew = $folder->addFolder('testnew');
		$this->assertEquals( $foldernew->exist(), true);
		$list = $this->folder->childs();
		$this->assertEquals( is_array( $list ), true);
		$foldernew->delete();
	
		$this->assertEquals( $foldernew->exist(), false);
		$list = $foldernew->childs(); 
		$this->assertEquals( is_array( $list ), true);
		$this->assertEquals( empty( $list ), true);
	
		$this->assertEquals( $this->folder->delete(), true);
		$this->assertEquals( $this->folder->exist(), false);
	}
	
	

}
