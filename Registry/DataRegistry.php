<?php


namespace Hnk\HnkFrameworkBundle\Registry;


use Hnk\HnkFrameworkBundle\Exception\HnkException;

class DataRegistry
{
    protected $data = [];

    /**
     * Sets value in data registry
     *
     * @param string $key
     * @param mixed $value
     * @param bool $override
     * @throws HnkException
     */
    public function set(string $key, $value, bool $override = false): void
    {
        if (isset($this->data[$key]) && !$override) {
            throw new HnkException(sprintf("Key %s already exists in registry", $key));
        }

        $this->data[$key] = $value;
    }

    /**
     * Returns data assigned to the key or default value
     *
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get(string $key, $defaultValue = null)
    {
        if ($this->isStored($key, false)) {
            return $this->data[$key];
        }

        return $defaultValue;
    }

    /**
     * Returns true if key exists in data registry
     * If $strict flag is used it additionally checks it value is null
     *
     * @param string $key
     * @param bool $strict
     * @return bool
     */
    public function isStored(string $key, bool $strict = true): bool
    {
        return ($strict) ? isset($this->data[$key]) : array_key_exists($key, $this->data);
    }
}