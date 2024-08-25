<?php

declare(strict_types=1);

namespace Seworqs\Laminas\Form;

use Doctrine\ORM\EntityManager;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Form\Element\Csrf;
use Laminas\Form\Element\Password;
use Laminas\Form\Element\Submit;
use Laminas\Form\Element\Text;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\NotEmpty;
use Seworqs\Laminas\Service\CurrentUserService;

class Login extends BaseForm
{
    public function __construct(CurrentUserService $currentUser, $options = [])
    {
        if (!isset($options['name'])) {
            $options['name'] = 'frmLogin';
        }

        parent::__construct($currentUser, $options);

        $this->addInputElements();
        $this->addInputFilters();
    }

    public function addInputElements()
    {

        // Add Csrf element.
        $this->add([
            'type' => Csrf::class,
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                    'timeout' => 600
                ]
            ]
        ]);
        $this->add([
            'name' => 'UserName',
            'type' => Text::class,
            'options' => [
                'label' => t('User name'),
                //'help_block' => t('Your user name'),
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => t('User name'),
            ]
        ]);
        $this->add([
            'name' => 'Password',
            'type' => Password::class,
            'options' => [
                'label' => t('Password'),
                //'help_block' => t('Your password'),
            ],
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => t('Password'),
            ]
        ]);
        $this->add([
            'name'       => 'btnSubmit',
            'type'       => Submit::class,
            'attributes' => [
                'value' => t('Login'),
                'id'    => 'btnSubmit',
                'class' => 'btn btn-primary btn-block'
            ],
        ]);
    }

    public function addInputFilters()
    {

        $inputFilter = new InputFilter();
        $inputFilter->add([
            'name' => 'UserName',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class]
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => t('This is a required field.'),
                        ]
                    ]
                ]
            ]
        ]);
        $inputFilter->add([
            'name' => 'Password',
            'required' => true,
            'filters' => [
                ['name' => StringTrim::class],
                ['name' => StripTags::class],
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'break_chain_on_failure' => true,
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => t('This is a required field.')
                        ]
                    ]
                ],
            ],
        ]);
        $this->setInputFilter($inputFilter);
    }
}
