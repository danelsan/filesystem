<?php

namespace danelsan\filesystem;


class File extends AbstractFilesystemNode implements IFile {

	private $handle;
	
	private $opened;
	
	private $open_type;
	
	public function __construct( $path ) {
		parent::__construct($path);
		$this->opened = false;
	}
	
	public function __destruct() {
		$this->close();
	}
	
	public function write( $content ) {
		if ( ! $this->isOpen() )
			throw new \Exception( 'File is not open ', 407 );

		if ( $this->open_type !== 'w' && $this->open_type !== 'a' ) 
			throw new \Exception( 'File is not open for write', 408 );
		
		fwrite( $this->handle, $content );
	}
	
	public function read( ) {
		if ( !$this->isOpen() ) 
			return FALSE;
		
		if ( $this->open_type !== 'r' )
			throw new \Exception( 'File is not open for read', 408 );
		
		$result = '';
		if ( $this->handle ) {
			while ( !feof($this->handle) ) {
				$result .= fread( $this->handle, 8192);
			}
			//$this->close();
		}
		return $result;
	}	
	
	public function save() {
	}
	
	public function open( $type = 'r' ) {
		if ( $this->isOpen() )
			$this->close();
			
		switch ( strtolower( $type ) ) {
			case 'r':
				try {
					$handle = fopen($this->path(), 'r' );
				} catch ( \Exception $e ) {
					throw new \Exception('Can not open file '.$this->path() . ' for reading' , 403);	
				}
				break;
			case 'w':
				try {
					$handle = fopen($this->path(), 'w' );
				} catch ( \Exception $e ) {
					throw new \Exception('Can not open file '.$this->path() . ' for writing' , 403);	
				}
				break;
			case 'a':
				try {
					$handle = fopen($this->path(), 'a' );
				} catch ( \Exception $e ) {
					throw new \Exception('Can not open file '.$this->path() . ' for writing' , 403);	
				}
				break;
			default:
				throw new \Exception('Type not valid for open file', 400);
				break;
		}
		$this->opened = true;
		$this->open_type = strtolower( $type );
		$this->handle = $handle;
	}
	
	public function close() {
		if ( $this->isOpen() ) {
			try {	
				fclose($this->handle);
				$this->handle = null;
				$this->opened = false;
				$this->open_type = null;
			} catch ( \Exception $e ) {
				throw new \Exception('Error to close file '.$this->path() , 402);	
			}
		}
	}
	
	public function isOpen() {
		return $this->opened;
	}
	
	public function delete() {
		if  ( !$this->isFile() )
			return False;
			
		if ( $this->isOpen() )
			$this->close();
		
		return @unlink( $this->path() );
	}
	
	public function rename( $newName ) {
		if ( !$this->isFile() )
			return False;
		
		
		if ( $this->isOpen() )
			$this->close();
		
		return parent::rename( $newName );
	}
	
	/**
	 * Copy a file into a directory
	 *
	 * @param IFilesystemNode $dir
	 * @return boolean
	 */
	public function copyTo( IFilesystemNode $dir, $force = FALSE ) {
		if ( $dir->isFile() )
			return FALSE;
		
		if ( $dir->path() == $this->parentFolder()->path() )
			return FALSE;
		
		if ( $force )
			$dir->make();
		
		if ( ! $dir->isDir() )
			return FALSE;
	
		return @copy( $this->path(), $dir->path() . $this->name() );
	}
	

}
