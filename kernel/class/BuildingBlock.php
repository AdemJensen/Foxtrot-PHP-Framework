<?php

namespace Chorg\Foundation;

class BuildingBlock
{
    /**
     * @var string The name of this Building Block.
     */
    private $name = "NULL";

    /**
     * @var string The version of this Building Block.
     */
    private $version = "NULL";

    /**
     * @var string The author of this Building Block.
     */
    private $author = "NULL";

    /**
     * @var DateTime The last update time of this Building Block.
     */
    private $updateTime = NULL;

    /**
     * @var array The required BBs of this Building Block.
     */
    private $requirements = array();

    /**
     * @var string The hashcode of this BB's full dir.
     * Used for validating the BB that a administrator has downloaded.
     */
    private $hashCode = "NULL";
}
