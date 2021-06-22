<?php

namespace App\Entity;

class AmountSetting
{
        
    /**
     * amountRegister
     *
     * @var mixed
     */
    private $amountRegister;
    
    /**
     * reductionRegister
     *
     * @var mixed
     */
    private $reductionRegister;
    
    /**
     * amountSubs
     *
     * @var mixed
     */
    private $amountSubs;
    
    /**
     * reductionSubs
     *
     * @var mixed
     */
    private $reductionSubs;
        
    /**
     * getAmountRegister
     *
     * @return int
     */
    public function getAmountRegister(): ?int
    {
        return $this->amountRegister;
    }
    
    /**
     * setAmountRegister
     *
     * @param  mixed $amountRegister
     * @return self
     */
    public function setAmountRegister(?int $amountRegister): self
    {
        $this->amountRegister = $amountRegister;

        return $this;
    }
    
    /**
     * getReductionRegister
     *
     * @return int
     */
    public function getReductionRegister(): ?int
    {
        return $this->reductionRegister;
    }
    
    /**
     * setReductionRegister
     *
     * @param  mixed $reductionRegister
     * @return self
     */
    public function setReductionRegister(?int $reductionRegister): self
    {
        $this->reductionRegister = $reductionRegister;

        return $this;
    }
    
    /**
     * getAmountSubs
     *
     * @return int
     */
    public function getAmountSubs(): ?int
    {
        return $this->amountSubs;
    }
    
    /**
     * setAmountSubs
     *
     * @param  mixed $amountSubs
     * @return self
     */
    public function setAmountSubs(?int $amountSubs): self
    {
        $this->amountSubs = $amountSubs;

        return $this;
    }
    
    /**
     * getReductionSubs
     *
     * @return int
     */
    public function getReductionSubs(): ?int
    {
        return $this->reductionSubs;
    }
    
    /**
     * setReductionSubs
     *
     * @param  mixed $reductionSubs
     * @return self
     */
    public function setReductionSubs(?int $reductionSubs): self
    {
        $this->reductionSubs = $reductionSubs;

        return $this;
    }
}
