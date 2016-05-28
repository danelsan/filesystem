<?php

namespace danelsan\filesystem;


/**
 * 
 * @author Daniele Frulla
 * @abstract
 * @version 1.0
 */
abstract class AbstractFilesystemNode implements IFilesystemNode {
	
	private $path;
	private $mimeType;
	private $size;
	private $created;
	private $modified;
	private $permission;
	private $owner;
	
	/**
	 * Setting path of file or directory
	 */
	public function __construct( $path  ) {
		$this->path = rtrim( trim($path) , '/');
	}
	
	/**
	 * Return the path of directory like string
	 *
	 * @return $path
	 */
	public function path() {
		return $this->path;
	}
	
	public function name() {
		$path = explode('/', rtrim($this->path(),'/') );
		return $path[count($path) - 1];
	}
	
	public function setDateCreate( $data ) {
		$this->created = $data;
	}
	
	public function setDateModified( $data ) {
		$this->modified = $data;
	}
	
	public function getCreated() {
		return $this->created;
	}
	
	public function getModified() {
		return $this->modified;
	}
	
	public function getMimeType() {
		return $this->mimeType;
	}
	
	public function setMimeType( $mimeType ) {
		$this->mimeType = $mimeType;
	}
	
	public function setOwner( $own ) {
		return chmod( $this->path(), $own );
	}
	
	public function getOwner() {
		return fileowner( $this->path() );
	}
	
	public function getPermisions()	{
		return substr(sprintf('%o', fileperms( $this->path() ) ), -4);
	}
	
	public function setPermission( $mode = '0755') {
		return chmod( $this->path(), $mode );
	}
		
	public function exist() {
		return file_exists( $this->path() );
	}
	
	public function isFile() {
		return is_file( $this->path() );
	}
	
	public function isDir() {
		return is_dir( $this->path() );
	}
	
	public function parentFolder() {
		$path = explode( '/', $this->path );
		unset($path[count($path) - 1]);
		$parentPath = implode('/', $path );
		return new Folder( $parentPath );
	}
	
	/**
	 * Return childs of a directory
	 *
	 * @return array of IFilesystemNode
	 */
	public function childs() {
		if ( ! $this->exist() )
			return array();
		
		if ( $this->isFile() )
			return array();
	
		if ( !$this->isDir() )
			return array();
			
		$ffs = scandir(  $this->path() );
		$result = array(); 
		foreach ( $ffs as $ff ) {
			if ($ff != '.' && $ff != '..' ) {
				if ( is_dir( $this->path(). DIRECTORY_SEPARATOR .$ff ) ) {
					$result[] = new Folder( $this->path() .$ff );
				} else {
					$result[] = new File( $this->path(). DIRECTORY_SEPARATOR .$ff );
				}
			}
		}
		return $result;
	}

	public function copyTo( IFilesystemNode $dir, $force = TRUE ) {
	
	}
	
	public function delete( ) {
		
	}

	public function rename( $newName ) {
		if ( !$this->exist() )
			throw new \Exception('Not exist file or directory', 409 );
			
		if ( @rename ( $this->path() , $this->parentFolder()->path() . $newName ) ) {
			$this->path = $this->parentFolder()->path() . $newName;
			return TRUE;
		}
		return FALSE;
	}
	
	public function moveTo( IFilesystemNode $file, $force = TRUE ) {
		if ( ! $this->copyTo($file, $force ) )
			return FALSE;
		
		$old_path = $this->path();
	
		if ( $this->isDir() ) {
			$old = new Folder( $old_path );
			$this->path = $file->path();
			return $old->delete();
		}
		
		if ( $this->isFile() ) {
			$old = new File( $old_path );
			$this->path = $file->path() . $this->name();
			return $old->delete();
		}
	}	

	public function __toString() {
		return $this->path();
	}
}
