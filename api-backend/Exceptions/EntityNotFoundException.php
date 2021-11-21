<?php
    
namespace App\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Class ObjectNotFoundException
 *
 * @package App\Exceptions
 */
class EntityNotFoundException extends RuntimeException
{
    /**
     * @var string
     */
    protected $objectName;
    
    /**
     * ObjectNotFoundException constructor.
     *
     * @param                  $objectName
     * @param  string          $message
     * @param  int             $code
     * @param  Throwable|null  $previous
     */
    public function __construct(string $objectName, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->objectName = $objectName;
        parent::__construct($message, $code, $previous);
    }
    
    public function getErrorMessage()
    {
        return "Entity {$this->objectName} not found";
    }
}
