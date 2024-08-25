<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Validator;

use Laminas\Validator\AbstractValidator;
use Seworqs\Laminas\Model\Account\AccountService;

class UniqueUserNameValidator extends AbstractValidator
{
    const USERNAME_EXISTS = 'usernameExists';
    // The message will be translated in the constructor.
    protected $messageTemplates = [
        self::USERNAME_EXISTS => 'The given username already exists.'
    ];
    protected $options = [
        'currentAccount' => null
    ];

    public function __construct($options = null)
    {
        parent::__construct($options);
        // For translating messages.
        $this->setMessages([self::USERNAME_EXISTS => t('User name is not available.')]);
    }

    public function isValid($value)
    {

        $this->setValue($value);
        $accountService = $this->getOption('accountService');
        $currentAccount = $this->getOption('currentAccount');
        if (! $accountService->isUniqueName($value, $currentAccount)) {
            $this->error(self::USERNAME_EXISTS);
            return false;
        }

        return true;
    }
}
