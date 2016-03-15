<?php 

namespace Opemedios\Http\Models;

class FuenteInternet extends Fuente{

	private $url;

	public function __construct($name, $url){
		parent::__construct($name);
	}




    /**
     * Gets the value of url.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets the value of url.
     *
     * @param mixed $url the url
     *
     * @return self
     */
    private function _setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}