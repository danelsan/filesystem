<?php

namespace danelsan\filesystem;

interface IFile extends IFilesystemNode {
	public function write( $content );
	public function read();
	public function save();
	public function open( $type );
	public function close();
	public function isOpen();
}
