<?php
namespace danelsan\filesystem;

interface IFolder extends IFilesystemNode {
	public function addFolder(  $name );
	public function addFile(  $name );
}
