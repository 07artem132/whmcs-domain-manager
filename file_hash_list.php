<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 10.05.2019
 * Time: 13:51
 */


$hashList = new CalculateHashProject( __DIR__ );
$hashList->addIgnorePath( __DIR__ . DIRECTORY_SEPARATOR . 'vendor' );
$hashList->addIgnorePath( __DIR__ . DIRECTORY_SEPARATOR . 'file_hash_list.php' );
$hashList->addFileType( 'php' );

foreach ( $hashList->getFileHashList() as $key => $item ) {
	echo $key . '=>' . $item . '<br/>';
}

class CalculateHashProject {
	private $ignores = [];
	private $AllowFileType = [];
	private $rootPath = '';
	private $fileList = [];
	private $fileHashList = [];

	function __construct( $rootPath ) {
		$this->rootPath = $rootPath;
	}

	function addIgnorePath( $path ) {
		$this->ignores[ $path ] = '';
	}

	function addFileType( $fileType ) {
		$this->AllowFileType[ $fileType ] = '';
	}

	function getExtension( $file ) {
		$extension = end( explode( ".", $file ) );

		return $extension ? $extension : false;
	}

	function getFilesList( $path = '' ) {
		$path  = empty( $path ) ? $this->rootPath : $path;
		$files = scandir( $path );

		foreach ( $files as $key => $value ) {
			$pathFile = realpath( $path . DIRECTORY_SEPARATOR . $value );

			if ( array_key_exists( $pathFile, $this->ignores ) ) {
				continue;
			}

			if ( ! is_dir( $pathFile ) ) {
				if ( array_key_exists( $this->getExtension( $pathFile ), $this->AllowFileType ) ) {
					$this->fileList[] = $pathFile;
				}
			} else if ( $value != "." && $value != ".." ) {
				$this->getFilesList( $pathFile );
			}
		}

		return $this->fileList;
	}

	function getFileHashList() {
		if ( empty( $this->fileList ) ) {
			$this->getFilesList();
		}

		foreach ( $this->fileList as $file ) {
			$this->fileHashList[ str_replace( $this->rootPath, "", $file ) ] = md5_file( $file );
		}

		return $this->fileHashList;
	}
}