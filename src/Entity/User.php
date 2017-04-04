<?php

namespace MIAAuthentication\Entity;

class User extends \MIABase\Entity\Base implements \Zend\InputFilter\InputFilterAwareInterface
{

    /**
     * @var int
     */
    public $id = null;

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
    public $photo = null;

    /**
     * @var string
     */
    public $facebook_id = null;

    public function toArray()
    {
        $data = parent::toArray();
        $data['id'] = $this->id;
        $data['mia_id'] = $this->mia_id;
        $data['firstname'] = $this->firstname;
        $data['lastname'] = $this->lastname;
        $data['email'] = $this->email;
        $data['photo'] = $this->photo;
        $data['facebook_id'] = $this->facebook_id;
        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;
        return $data;
    }

    public function exchangeArray(array $data)
    {
        parent::exchangeArray($data);
        $this->id = (!empty($data['id'])) ? $data['id'] : 0;
        $this->mia_id = (!empty($data['mia_id'])) ? $data['mia_id'] : 0;
        $this->firstname = (!empty($data['firstname'])) ? $data['firstname'] : '';
        $this->lastname = (!empty($data['lastname'])) ? $data['lastname'] : '';
        $this->email = (!empty($data['email'])) ? $data['email'] : '';
        $this->photo = (!empty($data['photo'])) ? $data['photo'] : '';
        $this->facebook_id = (!empty($data['facebook_id'])) ? $data['facebook_id'] : '';
    }

    public function exchangeObject($data)
    {
        parent::exchangeObject($data);
        $this->id = $data->id;
        $this->mia_id = $data->mia_id;
        $this->firstname = $data->firstname;
        $this->lastname = $data->lastname;
        $this->email = $data->email;
        $this->photo = $data->photo;
        $this->facebook_id = $data->facebook_id;
    }

    public function getInputFilter()
    {
        if ($this->inputFilter) {
            return $this->inputFilter;
        }
                
        $inputFilter = new \Zend\InputFilter\InputFilter();
        $inputFilter->add([
                    'name' => 'id',
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\ToInt::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'mia_id',
                    'required' => true,
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
                    'required' => true,
                    'filters' => [
                        ['name' => \Zend\Filter\StripTags::class],
                        ['name' => \Zend\Filter\StringTrim::class],
                    ],
                ]);
        $inputFilter->add([
                    'name' => 'facebook_id',
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

