<?php 

namespace Opemedios\Http\Models;


class Fuente{

	protected $id;

	protected $name;

	protected $category;

	protected $coment;

	protected $logo;

	protected $active;

	protected static $fontType = [
								'television', 
								'periodico',
								'internet', 
								'revista', 
								'radio' 
							  ];

	protected static $coverage = [
								'local', 
								'nacional', 
								'internacional'
							  ];

	public function __construct($name){
		$this->name = $name;
	}





    /**
     * Gets the value of coverage.
     *
     * @return mixed
     */
    public function getCoverage()
    {
        return $this->coverage;
    }

    /**
     * Sets the value of coverage.
     *
     * @param mixed $coverage the coverage
     *
     * @return self
     */
    protected function setCoverage($coverage)
    {
        $this->coverage = $coverage;

        return $this;
    }

    /**
     * Gets the value of id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the value of id.
     *
     * @param mixed $id the id
     *
     * @return self
     */
    protected function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param mixed $name the name
     *
     * @return self
     */
    protected function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value of category.
     *
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Sets the value of category.
     *
     * @param mixed $category the category
     *
     * @return self
     */
    protected function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Gets the value of coment.
     *
     * @return mixed
     */
    public function getComent()
    {
        return $this->coment;
    }

    /**
     * Sets the value of coment.
     *
     * @param mixed $coment the coment
     *
     * @return self
     */
    protected function setComent($coment)
    {
        $this->coment = $coment;

        return $this;
    }

    /**
     * Gets the value of logo.
     *
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Sets the value of logo.
     *
     * @param mixed $logo the logo
     *
     * @return self
     */
    protected function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Gets the value of active.
     *
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Sets the value of active.
     *
     * @param mixed $active the active
     *
     * @return self
     */
    protected function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Gets the value of fontType.
     *
     * @return mixed
     */
    public function getFontType()
    {
        return $this->fontType;
    }

    /**
     * Sets the value of fontType.
     *
     * @param mixed $fontType the font type
     *
     * @return self
     */
    protected function setFontType($fontType)
    {
        $this->fontType = $fontType;

        return $this;
    }
}