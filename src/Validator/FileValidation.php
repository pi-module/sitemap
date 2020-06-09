<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Sitemap\Validator;

use Pi;
use Laminas\Validator\AbstractValidator;

class FileValidation extends AbstractValidator
{
    const TAKEN = 'FileInvalid';

    /**
     * @var array
     */
    protected $messageTemplates
        = [
            self::TAKEN => 'XML file name is not valid, valid example is : sitemap1.xml, You should add .xml as file prefix and use a-z 1-9 on filename',
        ];

    public function isValid($value)
    {
        $this->setValue($value);
        if (preg_match('/^[a-z0-9-]+\.xml$/', $value)) {
            return true;
        } else {
            $this->error(static::TAKEN);
            return false;
        }
    }
}
