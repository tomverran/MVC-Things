<?php
/**
 * NotFound.php
 * @author Tom
 * @since 26/11/13
 */

namespace Framework\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NotFound extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Not Found');
    }
} 