<?php
namespace ProcessForward;

trait ProcessTrait{
        /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $isRun;

    /**
     * @var array
     */
    protected $parameter;

    /**
     * @var array
     */
    protected $result;

        /**
     * @var array
     */
    protected $error;

        /**
     * Get the value of id
     *
     * @return  string
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  string  $id
     *
     * @return  self
     */ 
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Get the value of isRun
     *
     * @return  bool
     */ 
    public function getIsRun()
    {
        return $this->isRun;
    }

    /**
     * Set the value of isRun
     *
     * @param  bool  $isRun
     *
     * @return  self
     */ 
    public function setIsRun(bool $isRun)
    {
        $this->isRun = $isRun;

        return $this;
    }

    /**
     * Get the value of parameter
     *
     * @return  array
     */ 
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set the value of parameter
     *
     * @param  array  $parameter
     *
     * @return  self
     */ 
    public function setParameter(array $parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Get the value of result
     *
     * @return  array
     */ 
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set the value of result
     *
     * @param  array  $result
     *
     * @return  self
     */ 
    public function setResult(array $result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get the value of error
     *
     * @return  array
     */ 
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set the value of error
     *
     * @param  array  $error
     *
     * @return  self
     */ 
    public function setError(array $error)
    {
        $this->error = $error;

        return $this;
    }
}