<?php

namespace App\DataFixtures;

use App\Entity\PostType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $postTypes = [
            [
                'name' => 'ویدیو',
                'description' => 'محتوای پست هایی از این نوع صرفا میتوانند شامل ویدیو و متن باشد',
            ],
            [
                'name' => 'تصویر',
                'description' => 'محتوای پست هایی از این نوع صرفا میتوانند شامل تصویر و متن باشد',
            ],
            [
                'name' => 'متن',
                'description' => 'محتوای پست هایی از این نوع صرفا میتوانند شامل متن باشد',
            ],
        ];

        foreach ($postTypes as $data) {
            $postType = new PostType();
            $postType->setName($data['name']);
            $postType->setDescription($data['description']);
            $manager->persist($postType);
        }

        $manager->flush();
    }
}
