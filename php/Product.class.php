<?php
/**
 * Product clas
 */
class Product
{
      protected ?float $price;
      protected ?string $name;
      protected ?int $id;
      

      public function __construct($row = false)
      {
            if ($row) {
                  $this->hydrate($row);
            }
      }
      public function hydrate($data)
      {
            foreach ($data as $key => $value) {
                  $method = "set" . ucfirst(str_replace("product_","",$key));
                  if(method_exists($this,$method))$this->{$method}($value);
            }
      }
      public function setPrice($value)
      {
            $value < 0 ? $this->price = 0 : $this->price = $value;
      }
      public function getPrice($raw = false)
      {
            return $raw ? $this->price : (is_null($this->price) ? "" : htmlspecialchars($this->price));
      }
      public function setName($value)
      {
            $this->name = $value;
      }
      public function getName($raw = false)
      {
            return $raw ? $this->name : (is_null($this->name) ? "" : htmlspecialchars($this->name));
      }
      public function setId($value)
      {
            $this->id = $value;
      }
      public function getId($raw = false)
      {
            return $raw ? $this->id : (is_null($this->id) ? "" : htmlspecialchars($this->id));
      }

}