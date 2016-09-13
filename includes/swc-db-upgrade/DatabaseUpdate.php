<?php 

	final class DatabaseUpdate {
			private $start;
			private $end; 
			private $dbScriptFolder='/dbscript';
			
			public function __construct($fromVersion, $toVersion){
				$this->start= $this->underscore($fromVersion);
		    	$this->end= $this->underscore($toVersion);
		    	
			}

		

		    public function upgrade(){
		    	require dirname ( __FILE__ ) . '/updater.php';
		    	$fileList = $this->GetFiles();

		    	$startPos = $this->GetFilePosition($fileList, $this->start) +1;
		    	
		    	$endPos  = $this->GetFilePosition($fileList, $this->end);

		    	foreach (new LimitIterator($fileList,$startPos ,$endPos ) as $file) {
		    		require $file;
		    		$className = pathinfo($file, PATHINFO_FILENAME);
		    		$updateClass = new $className;
				    $updateClass->update();
				    unset($className);
				 }
		    }

		    private function underscore($version){
		    	return '_'.str_replace('.', '_', $version);
		    }

		    private function GetFiles(){
		    	$iterator = new RecursiveDirectoryIterator(__DIR__ .$this->dbScriptFolder, RecursiveDirectoryIterator::SKIP_DOTS);

				$iterator =  new RecursiveCallbackFilterIterator(
				  $iterator,
				  function ($item) {
				    return $item->getExtension() === 'php' ? true : false;
				  }
				) ;

				return $iterator;
		    }

		    private function GetFilePosition($iterator, $filename){

			    	$files = array(); 

						foreach ($iterator  as $file) {

						    if ($file->isDir()){ 
						        continue;
						    }

					$files[] = pathinfo($file, PATHINFO_FILENAME); 
				   }

				return array_search($filename, $files);
			}

		}
?>