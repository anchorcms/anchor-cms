<?php

class Filesystem extends FilesystemIterator {

	public function getExtension() {
		if(method_exists(get_parent_class(), __METHOD__)) {
			return parent::getExtension();
		}

		return pathinfo($this->getFilename(), PATHINFO_EXTENSION);
	}

}