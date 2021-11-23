<?php

namespace App\Classe;


use App\Entity\Level;
use App\Entity\Product;
use App\Entity\Category;

class Search
{    
    /**
     * @var string
     */
    public $string = '';

    /**
     * @var Category[]
     */
    public $categories = [];
    
    /**
     * @var Level[]
     */
    public $levels = [];

    /**
     * @var Category[]
     */
    public $tags = [];

    /**
     * @var Product[]
     */
    public $product = [];
}
