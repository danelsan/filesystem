<?php

namespace danelsan\filesystem;

/**
 * Directory object for local filesystem Unix
 * 
 * @version 1.0
 */
class Folder extends AbstractFilesystemNode implements IFolder {
	
	public function path() {
		return parent::path() . '/';
	}
	
	public function copyTo( IFilesystemNode $dir,  $force = FALSE  ) {
		if (  !$this->isDir() )
			return FALSE;
		
		if ( $force )
			$dir->make();
		
		if ( !$dir->isDir() ) 
			return FALSE;
		
		$childs = $this->childs(); 
		$errors = FALSE;
		
		foreach ( $childs as $child ) {
			
			if ( $child->isDir() ) 
				$dest = new Folder( $dir->path() . $child->name() );
				
			if ( $child->isFile() )
				$dest = $dir;
				
			if ( !$child->copyTo( $dest, TRUE ) ) {
					// Some  problems
					$errors = TRUE;
					break;
			}
		}
		
		if ( $errors )
			return FALSE;
			
		return TRUE;
	}

	/**
	 * Make a directory and force the recursive creation
	 *
	 * @return boolean
	 */
	public function make() {
		if ( $this->exist() )
			return FALSE;
		
		return @mkdir( $this->path(), 0755,  TRUE );
	}
	
	public function getFolder( $name ) {
		return new Folder( $this->path() . $name );
	}
	
	/**
	 * Go to the file with the name
	 *
	 * @param $name
	 * @return File
	 */
	public function getFile( $name ) {
		return new File( $this->path() . $name );
	}
	
	/**
	 * Delete a directory
	 *
	 * @return boolean
	 */
	public function delete() {
		if ( !$this->isDir() )
			return FALSE;
		
		//Delete directory
		$childs = $this->childs();
	
		$errors = FALSE;
		foreach ( $childs as $child ) {	
			if ( ! $child->delete() ) {
				// Some  problems
				$errors = TRUE;
				break;
			}
		}		
		if ( $errors )
				return FALSE;
		return @rmdir( $this->path() );
	}
	
	public function addFolder( $name ) {
		return new Folder( $this->path() . $name );
	}
	
	public function addFile( $name ) {
		return new File( $this->path() . $name );
	}
	
}
