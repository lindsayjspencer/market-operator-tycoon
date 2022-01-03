<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Time extends DateTime {

				public function __construct($time="NOW") {
								parent::__construct($time);
								$this->setTimezone(new DateTimeZone("Australia/Brisbane"));
				}
    /**
     * Return Date in ISO8601 format
     *
     * @return String
     */
    public function __toString() {
        return $this->format('F jS g:ia');
    }

    /**
     * Return difference between $this and $now
     *
     * @param Datetime|String $now
     * @return DateInterval
     */
    public function difference($now = 'NOW') {
        if(!($now instanceOf DateTime)) {
            $now = new DateTime($now);
        }
        return parent::diff($now);
    }

    /**
     * Return Age in Years
     *
     * @param Datetime|String $now
     * @return Integer
     */
    public function getAge($now = 'NOW') {
        return $this->difference($now)->format('%y %m %d');
    }

}
?>
