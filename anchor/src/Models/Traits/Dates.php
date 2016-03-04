<?php

namespace Models\Traits;

trait Dates {

	protected function getRelativeTime(\DateTime $date) {
		$now = new \DateTime;
		$diff = $now->diff($date);

		if($diff->format('%d') < 1) {
			return 'Today ' . $date->format('h:ia');
		}

		if($diff->format('%d') < 3) {
			return 'Yesterday ' . $date->format('h:ia');
		}

		if($diff->format('%y') < 1) {
			return $date->format('jS F');
		}

		return $date->format('jS M, Y');
	}

	public function getCreatedDate() {
		return \DateTime::createFromFormat('Y-m-d H:i:s', $this->created);
	}

	public function getRelativeCreatedDate() {
		return $this->getRelativeTime($this->getCreatedDate());
	}

	public function getModifiedDate() {
		return \DateTime::createFromFormat('Y-m-d H:i:s', $this->modified);
	}

	public function getRelativeModifiedDate() {
		return $this->getRelativeTime($this->getModifiedDate());
	}

}
