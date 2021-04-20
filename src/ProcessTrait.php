<?php
namespace ProcessForward;

trait ProcessTrait{
        /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $owned;
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
     * @var int
     */
    protected $time;

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

    /**
     * Get the value of owned
     *
     * @return  string
     */ 
    public function getOwned()
    {
        if(!$this->owned) $this->owned = "ghost";
        return $this->owned;
    }

    /**
     * Set the value of owned
     *
     * @param  string  $owned
     *
     * @return  self
     */ 
    public function setOwned(string $owned)
    {
        $this->owned = $owned;

        return $this;
    }

    /**
     * Get the value of time
     *
     * @return  int
     */ 
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the value of time
     *
     * @param  int  $time
     *
     * @return  self
     */ 
    public function setTime(int $time)
    {
        $this->time = $time;

        return $this;
    }
}