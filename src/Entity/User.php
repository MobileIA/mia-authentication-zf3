<?php

namespace MIAAuthentication\Entity;

class User extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{
    const ROLE_MEMBER = 0;
    const ROLE_ADMIN = 1;
    
    /**
     * @var int
     */
    public $mia_id = null;

    /**
     * @var string
     */
    public $firstname = null;

    /**
     * @var string
     */
    public $lastname = null;

    /**
     * @var string
     */
    public $email = null;

    /**
     * @var string
     */
    public $phone = null;
    
    /**
     * @var string
     */
    public $photo = null;

    /**
     * @var string
     */
    public $facebook_id = null;
    
    /**
     * @var int
     */
    public $role = null;

    public $password = null;

    public function toArray()
    {
        $data = parent::toArray();
        $data['mia_id'] = $this->mia_id;
        $data['firstname'] = $this->firstname;
        $data['lastname'] = $this->lastname;
        $data['email'] = $this->email;
        $data['photo'] = $this->photo;
        $data['phone'] = $this->phone;
        $data['facebook_id'] = $this->facebook_id;
        $data['role'] = $this->role;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->mia_id = (!empty($data['mia_id'])) ? $data['mia_id'] : 0;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : '';
        $this->lastname = (!empty($data['lastname'])) ? $data['lastname'] : '';
        $this->email = (!empty($data['email'])) ? $data['email'] : '';
        $this->photo = (!empty($data['photo'])) ? $data['photo'] : '';
        $this->phone = (!empty($data['phone'])) ? $data['phone'] : '';
        $this->facebook_id = (!empty($data['facebook_id'])) ? $data['facebook_id'] : '';
        $this->role = (!empty($data['role'])) ? $data['role'] : 0;
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->mia_id = $data->mia_id;
        $this->firstname = $data->firstname;
        $this->lastname = $data->lastname;
        $this->email = $data->email;
        $this->photo = $data->photo;
        $this->phone = $data->phone;
        $this->facebook_id = $data->facebook_id;
        $this->role = $data->role;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
                
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'mia_id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'role',
                    'required' => false,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'firstname',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'lastname',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                    'validators' => [
                        [
                            'name' => \Zend\Validator\StringLength::class,
                            'options' => [
                                'encoding' => 'UTF-8',
                                'min' => 1,
                                'max' => 100,
                            ],
                        ],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'email',
                    'required' => true,
                    'validators' => [
                        [
                            'name' => \Zend\Validator\EmailAddress::class,
                        ],
                    ],
                ]);
        $inputFilter->add([
            'name' => 'photo',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
        ]);
        $inputFilter->add([
            'name' => 'phone',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
        ]);
        $inputFilter->add([
            'name' => 'facebook_id',
            'required' => false,
            'filters' => [
                ['name' => \Zend\Filter\StripTags::class],
                ['name' => \Zend\Filter\StringTrim::class],
            ],
        ]);


        $this->inputFilter = $inputFilter;
        return $this->inputFilter;
    }

    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {
        throw new DomainException(sprintf(
                    '%s does not allow injection of an alternate input filter',
                    __CLASS__
                ));
    }


}

