<?php

class Storage_Driver_PhpVar extends Base_Class implements Storage_Driver, Storage_ArrayKey, Storage_ExportImportArray {
    public function __construct(Storage_Dsn $dsn = null) {
        $this->dsn = $dsn ? $dsn : new Storage_Dsn();
        $this->time = Yaoi::time($this->dsn->dateSource); // TODO use getInstance after Date_Source refactoring
    }

    protected $data = array();
    protected $modified = false;

    public function set($key, $value, $ttl) {
        if (is_array($key)) {
            $kk =& $this->data;
            foreach ($key as $k) {
                $kk =& $kk[$k];
            }
            $kk = $value;
        }
        else {
            if (null === $key) {
                $this->data []= $value;
            }
            else {
                $this->data[$key] = $value;
            }
        }

        $this->modified = true;
    }

    public function get($key) {
        if (is_array($key)) {
            $kk =& $this->data;
            foreach ($key as $k) {
                if (array_key_exists($k, $kk)) {
                    $kk =& $kk[$k];
                }
                else {
                    return null;
                }
            }
            return $kk;
        }
        elseif (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        elseif (null === $key) {
            return $this->data;
        }
        else {
            return null;
        }
    }

    public function keyExists($key) {
        if (is_array($key)) {
            $kk =& $this->data;
            foreach ($key as $k) {
                if (array_key_exists($k, $kk)) {
                    $kk =& $kk[$k];
                }
                else {
                    return false;
                }
            }
            return true;
        }
        elseif (array_key_exists($key, $this->data)) {
            return true;
        }
        elseif (null === $key) {
            return true;
        }
        else {
            return false;
        }
    }

    public function delete($key)
    {
        if (is_array($key)) {
            $kk =& $this->data;
            $lastKey = null;
            $parent = null;
            foreach ($key as $k) {
                $parent =& $kk;
                $lastKey = $k;
                if (array_key_exists($k, $kk)) {
                    $kk =& $kk[$k];
                }
                else {
                    return;
                }
            }
            if ($parent && array_key_exists($lastKey, $parent)) {
                unset($parent[$lastKey]);
                $this->modified = true;
            }
        }
        elseif (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
            $this->modified = true;
        }
    }

    public function deleteAll()
    {
        if ($this->data) {
            $this->modified = true;
        }

        $this->data = array();
    }

    public function &exportArray()
    {
        return $this->data;
    }

    public function importArray(array &$data)
    {
        $this->data = $data;
        $this->modified = true;
    }

}