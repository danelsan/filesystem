<?php

namespace danelsan\filesystem;


/**
 * 
 * @author Daniele Frulla
 * @interface
 * @version 1.0
 */
interface IFilesystemNode {

	public function path();
	
	public function name();
	
	public function setDateCreate( $data );
	
	public function setDateModified( $data );
	
	public function getCreated();
	
	public function getModified();
	
	public function getMimeType();
	
	public function setMimeType( $mimeType );
	
	public function setOwner( $own );
	
	public function getOwner();
	
	public function getPermisions();
	
	public function setPermission( $mode = '0755');
		
	public function exist();
	
	public function isFile();
	
	public function isDir();

	public function parentFolder();
	
	public function childs();

	public function copyTo( IFilesystemNode $file, $force = TRUE );
	
	public function delete( );

	public function rename( $newName );
	
	public function moveTo( IFilesystemNode $file, $force = TRUE );

}
