<?php

namespace plataforma\view\json;

interface JsonSerializable {
    /**
     * @return array
     */
    public function jsonSerialize();
}